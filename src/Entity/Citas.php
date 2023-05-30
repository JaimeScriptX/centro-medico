<?php

namespace App\Entity;

use App\Repository\CitasRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CitasRepository::class)
 */
class Citas
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Especialidades::class, inversedBy="citas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $especialidad;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="El dni no puede estar vacio")
     */
    private $dni;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="El nombre no puede estar vacio")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La direccion no puede estar vacia")
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="El telefono no puede estar vacio")
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="El email no puede estar vacio")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $detalles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEspecialidad(): ?especialidades
    {
        return $this->especialidad;
    }

    public function setEspecialidad(?especialidades $especialidad): self
    {
        $this->especialidad = $especialidad;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

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

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

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

    public function getDetalles(): ?string
    {
        return $this->detalles;
    }

    public function setDetalles(?string $detalles): self
    {
        $this->detalles = $detalles;

        return $this;
    }
}
