<?php

namespace App\Mailer;

use Symfony\Component\Mailer\MailerInterface;

class ContactMailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($formData)
    {
        $email = (new Email())
            ->from($formData['email'])
            ->to('info@yourbesthost.com')
            ->subject('Nuevo mensaje de contacto')
            ->html($this->renderEmailTemplate($formData));

        $this->mailer->send($email);
    }

    private function renderEmailTemplate($formData)
    {
        // Puedes personalizar el contenido del correo electrónico aquí utilizando los datos del formulario

        return "<p>Nombre: {$formData['name']}</p>"
            . "<p>Email: {$formData['email']}</p>"
            . "<p>Mensaje: {$formData['message']}</p>";
    }
}
