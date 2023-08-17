<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Property;
use App\Form\ImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PropertyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Property::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('area'),
            NumberField::new('lat'),
            NumberField::new('longitude'),
            TextField::new('location'),
            TextEditorField::new( 'description' )->setTemplatePath( 'texteditorfield/editortextfieldTemplate.html.twig' ),
            NumberField::new('price'),
            IntegerField::new('guests'),
            IntegerField::new('bedrooms'),
            IntegerField::new('toilets'),
            AssociationField::new('realState', 'Tipo de propiedad'),
            TextField::new('url')->hideOnIndex(),
            CollectionField::new('images')
                ->setEntryType(ImageType::class)
                ->setFormTypeOption( 'by_reference', false)
                ->onlyOnForms(),
            CollectionField::new('images')
                ->setTemplatePath('main/image.html.twig')
                ->onlyOnDetail()
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(CRUD::PAGE_INDEX, 'detail');
    }

}
