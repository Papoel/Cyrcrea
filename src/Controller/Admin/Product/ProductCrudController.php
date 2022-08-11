<?php

namespace App\Controller\Admin\Product;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public const PRODUCT_BASE_PATH = 'images/telechargements/produits';
    public const PRODUCT_UPLOAD_DIR = 'public/images/telechargements/produits/';

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name', 'Nom');
        yield SlugField::new('slug', 'Slug')
            ->setTargetFieldName('name');
        yield TextField::new('description', 'Description');
        yield TextEditorField::new('more_informations', 'Informations complémentaires')
            ->onlyOnDetail()
        ;

        yield MoneyField::new('price', 'Prix')
            ->setCurrency('EUR');
        yield BooleanField::new('is_Best', 'Best');
        yield BooleanField::new('is_New_Arrival', 'Nouveau');
        yield BooleanField::new('is_Featured');

        yield ImageField::new('image', 'Image')
                ->setBasePath(self::PRODUCT_BASE_PATH)
                ->setUploadDir(self::PRODUCT_UPLOAD_DIR)
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false);
                // ->setFormTypeOption('allow_delete', false)
        yield IntegerField::new('stock', 'Quantité');

        // Transform the field tag in array on index and in string on edit
        yield TextField::new('tags', 'Cible');

        if ($pageName === 'edit') {
            yield AssociationField::new('Categories', 'Catégories')
                ->setFormTypeOptions([
                    'multiple' => true,
                    'expanded' => false,
                ]);
        }
    }
}
