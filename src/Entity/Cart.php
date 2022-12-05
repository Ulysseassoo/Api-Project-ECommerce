<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['default'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['default'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['default'])]
    private ?int $totalAmount = null;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist'])]
    #[Groups(['client'])]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: ProductCart::class)]
    private Collection $productCarts;


    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->productCarts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, ProductCart>
     */
    public function getProductCarts(): Collection
    {
        return $this->productCarts;
    }

    public function addProductCart(ProductCart $productCart): self
    {
        if (!$this->productCarts->contains($productCart)) {
            $this->productCarts->add($productCart);
            $productCart->setCart($this);
        }

        return $this;
    }

    public function removeProductCart(ProductCart $productCart): self
    {
        if ($this->productCarts->removeElement($productCart)) {
            // set the owning side to null (unless already changed)
            if ($productCart->getCart() === $this) {
                $productCart->setCart(null);
            }
        }

        return $this;
    }

}