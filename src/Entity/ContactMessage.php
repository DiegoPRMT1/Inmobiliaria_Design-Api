<?php

namespace App\Entity;

use App\Repository\ContactMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContactMessageRepository::class)
 */
class ContactMessage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    public $realEstate_type;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    public $n_bedrooms;

    /**
     * @Assert\Email
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $details;

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

    public function getRealEstateType(): ?string
    {
        return $this->realEstate_type;
    }

    public function setRealEstateType(string $realEstate_type): self
    {
        $this->realEstate_type = $realEstate_type;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getNBedrooms(): ?string
    {
        return $this->n_bedrooms;
    }

    public function setNBedrooms(string $n_bedrooms): self
    {
        $this->n_bedrooms = $n_bedrooms;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }
}
