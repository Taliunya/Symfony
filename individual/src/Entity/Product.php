<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $product_number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductNumber(): ?string
    {
        return $this->product_number;
    }

    public function setProductNumber(string $product_number): self
    {
        $this->product_number = $product_number;

        return $this;
    }
}
