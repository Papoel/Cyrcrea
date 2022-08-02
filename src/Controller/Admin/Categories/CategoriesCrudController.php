<?php

namespace App\Controller\Admin\Categories;

use App\Entity\Categories;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoriesCrudController extends AbstractCrudController
{
    public const CATEGORIES_BASE_PATH = 'images/telechargements/categories/';
    public const CATEGORIES_UPLOAD_DIR = 'public/images/telechargements/categories/';

    public static function getEntityFqcn(): string
    {
        return Categories::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            TextEditorField::new('description', 'Description'),
            ImageField::new('image', 'Image')
                ->setBasePath(self::CATEGORIES_BASE_PATH)
                ->setUploadDir(self::CATEGORIES_UPLOAD_DIR)
                ->setUploadedFileNamePattern('[entityName]_[id]_[fieldName]_[sha1(file.originalFilename)]')
        ];
    }

}
