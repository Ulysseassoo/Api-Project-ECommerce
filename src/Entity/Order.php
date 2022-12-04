<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['default', 'order'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['default', 'order'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['default', 'order'])]
    private ?float $total = null;

    #[ORM\Column(length: 255)]
    #[Groups(['default', 'order'])]
    private ?string $code = null;

    #[ORM\Column]
    #[Groups(['default', 'order'])]
    private ?int $payment_method = null;

    #[ORM\Column(length: 255)]
    #[Groups(['default', 'order'])]
    private ?string $address_used = null;

    #[ORM\OneToMany(mappedBy: 'command', targetEntity: OrderEntry::class)]
    #[Groups(['order'])]
    private Collection $orderEntries;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['client'])]
    private ?Client $client = null;

    public function __construct()
    {
        $this->orderEntries = new ArrayCollection();
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

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPaymentMethod(): ?int
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(int $payment_method): self
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getAddressUsed(): ?string
    {
        return $this->address_used;
    }

    public function setAddressUsed(string $address_used): self
    {
        $this->address_used = $address_used;

        return $this;
    }

    /**
     * @return Collection<int, OrderEntry>
     */
    public function getOrderEntries(): Collection
    {
        return $this->orderEntries;
    }

    public function addOrderEntry(OrderEntry $orderEntry): self
    {
        if (!$this->orderEntries->contains($orderEntry)) {
            $this->orderEntries->add($orderEntry);
            $orderEntry->setCommand($this);
        }

        return $this;
    }

    public function removeOrderEntry(OrderEntry $orderEntry): self
    {
        if ($this->orderEntries->removeElement($orderEntry)) {
            // set the owning side to null (unless already changed)
            if ($orderEntry->getCommand() === $this) {
                $orderEntry->setCommand(null);
            }
        }

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
}
