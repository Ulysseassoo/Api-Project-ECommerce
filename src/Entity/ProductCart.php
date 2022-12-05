<?php

namespace App\Entity;

use App\Repository\ProductCartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductCartRepository::class)]
class ProductCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['default'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productCarts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(inversedBy: 'productCarts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['default'])]
    private ?Product $product = null;

    #[ORM\Column]
    #[Groups(['default'])]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
