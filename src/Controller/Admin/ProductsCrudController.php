<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextField::new('description'),
            ChoiceField::new('category', 'Category')
                ->allowMultipleChoices()
                ->autocomplete()
                ->setChoices([
                    'Ordenadores' => 'Computer',
                    'Videojuegos' => 'Videogames',
                    'Consolas' => 'Videoconsoles',
                    'Accessories' => 'Accessories',
                    
                ]),


            NumberField::new('stock'),
            NumberField::new('price')->setLabel('Precio (€)'),
            ImageField::new('image')
            ->setBasePath('/images/products')
            ->setUploadDir('public/images/products'),
            
            
            
        ];
    }
}
