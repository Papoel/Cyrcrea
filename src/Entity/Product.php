<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 80)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $slug;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private string $moreInformations;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $isBest;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $isNewArrival;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $isFeatured;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $isSpecialOffer;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $image;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $tags;

    #[ORM\OneToMany(mappedBy: 'productId', targetEntity: Review::class)]
    private Product $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMoreInformations(): ?string
    {
        return $this->moreInformations;
    }

    public function setMoreInformations(?string $moreInformations): self
    {
        $this->moreInformations = $moreInformations;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isIsBest(): ?bool
    {
        return $this->isBest;
    }

    public function setIsBest(?bool $isBest): self
    {
        $this->isBest = $isBest;

        return $this;
    }

    public function isIsNewArrival(): ?bool
    {
        return $this->isNewArrival;
    }

    public function setIsNewArrival(?bool $isNewArrival): self
    {
        $this->isNewArrival = $isNewArrival;

        return $this;
    }

    public function isIsFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(?bool $isFeatured): self
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function isIsSpecialOffer(): ?bool
    {
        return $this->isSpecialOffer;
    }

    public function setIsSpecialOffer(?bool $isSpecialOffer): self
    {
        $this->isSpecialOffer = $isSpecialOffer;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProductId($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProductId() === $this) {
                $review->setProductId(null);
            }
        }

        return $this;
    }
}
