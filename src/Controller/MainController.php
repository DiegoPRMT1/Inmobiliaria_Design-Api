<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Entity\ContactoEmail;
use App\Entity\Property;
use App\Entity\RealState;
use App\Form\ContactMessageType;
use App\Form\ContactoEmailType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class MainController extends AbstractController
{
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/{_locale}/", name="app_main")
     */
    public function index(Request $request): Response
    {
        $properties = $this->em->getRepository(Property::class)->findAll();
        $realStates = $this->em->getRepository(RealState::class)->findAll();
        $search = $request->query->get('search');
        if (null == $search) {
            $query = $this->em->getRepository(Property::class)->findAll();
        } else {
            $query = $this->em->getRepository(Property::class)->searchPost($search);
        }
        return $this->render('main/index.html.twig', [
            'properties' => $properties,
            'lista' => $query,
            'realStates' => $realStates
        ]);
    }

    /**
     * @Route("/{_locale}/propiedades", name="app_propiedades")
     */
    public function properties(): Response
    {
        $houses = $this->em->getRepository(Property::class)->findAllHouses();
        $apartments = $this->em->getRepository(Property::class)->findAllApartments();
        $villas = $this->em->getRepository(Property::class)->findAllVilles();
        return $this->render('main/propiedades.html.twig', [
            'houses' => $houses,
            'apartments' => $apartments,
            'villas' => $villas
        ]);
    }

    /**
     * @Route("/{_locale}/single/{id}", name="app_single")
     */
    public function single(Property $property): Response
    {
        return $this->render('property/single_property.html.twig', [
            'property' => $property
        ]);
    }

    /**
     * @Route("/{_locale}/nosotros", name="app_aboutus")
     */
    public function aboutUs(): Response
    {
        return $this->render('main/about_us.html.twig');
    }

    /**
     * @Route("/{_locale}/contacto", name="app_contacto")
     * @throws TransportExceptionInterface
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $contactMessage = new ContactoEmail();
        $form = $this->createForm(ContactoEmailType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $name = $data->getName();
            $address = $data->getEmail();
            $details = $data->getContent();

            // Concatenar las variables en una cadena de texto
            $content = "Nombre: " . $name . "\n"
                . "Email: " . $address . "\n"
                . "Detalles: " . $details;

            $email = (new Email())
                ->from($address)
                ->to('diego@someandco.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Contacto')
                ->text($content);

            $mailer->send($email);

            // ...
            return $this->redirectToRoute('app_contacto');
        }

        return $this->render('main/gestiones.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{_locale}/gestion", name="app_gestion")
     */
    public function management(Request $request, MailerInterface $mailer): Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Realiza alguna acción, como enviar un correo electrónico o guardar los datos en la base de datos
            $data = $form->getData();
            // Redirige a una página de éxito o muestra un mensaje de éxito
            //$address = $data['email'];
            //$content = $data['content'];
            $name = $data->getName();
            $realEstateType = $data->getRealEstateType();
            $direccion = $data->getDireccion();
            $bedrooms = $data->getNBedrooms();
            $address = $data->getEmail();
            $details = $data->getDetails();

            // Concatenar las variables en una cadena de texto
            $content = "Nombre: " . $name . "\n"
                . "Tipo de inmueble: " . $realEstateType . "\n"
                . "Dirección: " . $direccion . "\n"
                . "Habitaciones: " . $bedrooms . "\n"
                . "Email: " . $address . "\n"
                . "Detalles: " . $details;

            $email = (new Email())
                ->from($address)
                ->to('pedro@someandco.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Gestion de inmuebles personales')
                ->text($content);

            $mailer->send($email);

            // ...
            return $this->redirectToRoute('app_gestion');
        }

        return $this->render('main/gestion.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{_locale}/propiedad/{area}/{real_estate}",
     *      name="app_propiedad",
     *     options={"expose"=true},
     *     methods = {"GET"})
     */
    public function propertyTypes(string $area, string $real_estate): Response
    {
        if ($area === 'default') {
            $byAreaAndRealEstate = $this->em->getRepository(Property::class)->findPropertiesByRealEstate($real_estate);
        } else {
            $byAreaAndRealEstate = $this->em->getRepository(Property::class)->findPropertiesByAreaAndRealEstate($area, $real_estate);
        }


        return $this->render('main/tipo_propiedades.html.twig', [
            'area' => $area,
            'realEstate' => $real_estate,
            'byAreaAndRealEstate' => $byAreaAndRealEstate
        ]);
    }

    /**
     * @Route("/{_locale}/propiedad/{area}/{real_estate}/{price}/{zone}/{bedrooms}",
     *     name="app_filterby",
     *     options={"expose"=true},
     *     condition="request.isXmlHttpRequest()",
     *     methods={"POST"},
     *     defaults={"price"=null, "zone"=null, "bedrooms"=null}
     * )
     */
    public function filterBy(string $area, string $real_estate, ?float $price, ?string $zone, ?int $bedrooms): JsonResponse
    {
       try{
           $filter = $this->em->getRepository(Property::class)->filterProperty($price, $zone, $bedrooms);

           return new JsonResponse([
              'status' => 'ok',
              'filter' => $filter
           ]);

       }catch (Exception $e){
           return new JsonResponse([
               'status' => 'error',
               'messages' => 'El formulario no es valido',
           ]);
       }
    }

}
