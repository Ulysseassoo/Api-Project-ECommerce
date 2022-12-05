<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['default'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['default'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['default'])]
    private ?int $quantity = null;

    #[ORM\Column]
    #[Groups(['default'])]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['default'])]
    private ?string $shortDescription = null;

    #[ORM\Column(length: 255)]
    #[Groups(['default'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['default'])]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductCart::class)]
    private Collection $productCarts;


    public function __construct()
    {
        $this->cart = new ArrayCollection();
        $this->productCarts = new ArrayCollection();
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
            $productCart->setProduct($this);
        }

        return $this;
    }

    public function removeProductCart(ProductCart $productCart): self
    {
        if ($this->productCarts->removeElement($productCart)) {
            // set the owning side to null (unless already changed)
            if ($productCart->getProduct() === $this) {
                $productCart->setProduct(null);
            }
        }

        return $this;
    }
}
