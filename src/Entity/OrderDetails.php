<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
class OrderDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'text')]
    private string $productName;

    #[ORM\Column(type: 'float')]
    private float $productPrice;

    #[ORM\Column(type: 'text')]
    private string $quantity;

    #[ORM\Column(type: 'text')]
    private string $subTotalHt;

    #[ORM\Column(type: 'float')]
    private float $taxe;

    #[ORM\Column(type: 'float')]
    private float $subTotalTtc;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private Order $orderId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(float $productPrice): self
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

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(float $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getSubTotalTtc(): ?float
    {
        return $this->subTotalTtc;
    }

    public function setSubTotalTtc(float $subTotalTtc): self
    {
        $this->subTotalTtc = $subTotalTtc;

        return $this;
    }

    public function getOrderId(): ?Order
    {
        return $this->orderId;
    }

    public function setOrderId(?Order $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }
}
