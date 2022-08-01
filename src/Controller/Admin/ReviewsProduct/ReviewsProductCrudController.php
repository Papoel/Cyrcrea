<?php

namespace App\Controller\Admin\ReviewsProduct;

use App\Entity\ReviewsProduct;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReviewsProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReviewsProduct::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
