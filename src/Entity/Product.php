<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "list_products"})
     * @var int|null
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "list_products"})
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="text")
     * @Groups({"list", "list_products"})
     * @var string|null
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "list_products"})
     * @var string|null
     */
    private ?string $price = null;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Groups({"list", "list_products"})
     * @var DateTimeInterface|null
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToMany(targetEntity=Client::class, inversedBy="products")
     */
    private Collection $clients;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string|null $price
     * @return $this
     */
    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param ?DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
        }

        return $this;
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function removeClient(Client $client): self
    {
        if ($this->clients->contains($client)) {
            $this->clients->removeElement($client);
        }

        return $this;
    }
}
