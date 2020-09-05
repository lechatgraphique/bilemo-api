<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @OA\Schema()
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user"})
     * @var int|null
     * @OA\Property(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user"})
     * @OA\Property(type="string")
     * @Assert\NotBlank(groups={"user"}, message="Vous devez saisir un nom d'utilisateur.")
     */
    private ?string $username = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user"})
     * @var string|null
     * @OA\Property(type="string")
     * @Assert\Email(groups={"user"}, message="Le format de l'adresse n'est pas correcte.")
     */
    private ?string $email = null;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users")
     * @OA\Property(type="string")
     *
     */
    private ?Client $client = null;

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
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     * @return $this
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client|null $client
     * @return $this
     */
    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
