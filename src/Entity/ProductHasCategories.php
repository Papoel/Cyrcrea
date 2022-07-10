<?php

namespace App\Entity;

use App\Repository\ProductHasCategoriesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductHasCategoriesRepository::class)]
class ProductHasCategories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    private Product $productId;

    #[ORM\ManyToOne(targetEntity: Categories::class)]
    private Categories $categoriesId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?Product
    {
        return $this->productId;
    }

    public function setProductId(?Product $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getCategoriesId(): ?Categories
    {
        return $this->categoriesId;
    }

    public function setCategoriesId(?Categories $categoriesId): self
    {
        $this->categoriesId = $categoriesId;

        return $this;
    }
}
