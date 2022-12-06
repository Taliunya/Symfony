<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShopRepository::class)]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $count = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[ORM\OneToMany(mappedBy: 'shop', targetEntity: ShopProduct::class, orphanRemoval: true)]
    private Collection $shopProducts;

    public function __construct()
    {
        $this->shopProducts = new ArrayCollection();
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

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

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

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, ShopProduct>
     */
    public function getShopProducts(): Collection
    {
        return $this->shopProducts;
    }

    public function addShopProduct(ShopProduct $shopProduct): self
    {
        if (!$this->shopProducts->contains($shopProduct)) {
            $this->shopProducts->add($shopProduct);
            $shopProduct->setShop($this);
        }

        return $this;
    }

    public function removeShopProduct(ShopProduct $shopProduct): self
    {
        if ($this->shopProducts->removeElement($shopProduct)) {
            // set the owning side to null (unless already changed)
            if ($shopProduct->getShop() === $this) {
                $shopProduct->setShop(null);
            }
        }

        return $this;
    }
}
