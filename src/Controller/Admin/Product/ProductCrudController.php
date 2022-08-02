<?php

namespace App\Controller\Admin\Product;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public const PRODUCT_BASE_PATH = 'images/telechargements/';
    public const PRODUCT_UPLOAD_DIR = 'public/images/telechargements/produits/';

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name', 'Nom');
        yield TextField::new('description', 'Description');
        yield MoneyField::new('price', 'Prix')->setCurrency('EUR');
        yield BooleanField::new('is_Best', 'Best');
        yield BooleanField::new('is_New_Arrival', 'Nouveau');
        yield BooleanField::new('is_Featured');
        yield ImageField::new('image', 'Image')
                ->setBasePath(self::PRODUCT_BASE_PATH)
                ->setUploadDir(self::PRODUCT_UPLOAD_DIR);

        if ($pageName === 'edit') {
            yield AssociationField::new('Categories', 'Catégories')
                ->setFormTypeOptions([
                    'multiple' => true,
                    'expanded' => false,
                ]);
        }
        //yield AssociationField::new('categories', 'Catégories');
    }
}
