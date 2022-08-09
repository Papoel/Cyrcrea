<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
class OrderDetails
{
    #[ORM\Column(type: 'text')]
    private string $productName;

    #[ORM\Column(type: 'integer')]
    private int $productPrice;

    #[ORM\Column(type: 'text')]
    private string $quantity;

    #[ORM\Column(type: 'text')]
    private string $subTotalHt;

    #[ORM\Column(type: 'integer')]
    private int $taxe;

    #[ORM\Column(type: 'integer')]
    private int $subTotalTtc;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private Order $order;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private Products $products;

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?int
    {
        return $this->productPrice;
    }

    public function setProductPrice(int $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSubTotalHt(): ?string
    {
        return $this->subTotalHt;
    }

    public function setSubTotalHt(string $subTotalHt): self
    {
        $this->subTotalHt = $subTotalHt;

        return $this;
    }

    public function getTaxe(): ?int
    {
        return $this->taxe;
    }

    public function setTaxe(int $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getSubTotalTtc(): ?int
    {
        return $this->subTotalTtc;
    }

    public function setSubTotalTtc(int $subTotalTtc): self
    {
        $this->subTotalTtc = $subTotalTtc;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getProducts(): ?Products
    {
        return $this->products;
    }

    public function setProducts(?Products $products): self
    {
        $this->products = $products;

        return $this;
    }
}
