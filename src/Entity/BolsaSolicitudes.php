<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BolsaSolicitudesRepository::class)
 */
class BolsaSolicitudes
{
    /**
     * @var int
     *
     * @ORM\Column(name="solicitud_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $solicitudId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dni", type="string", length=255, nullable=true)
     */
    private $dni;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", length=45, nullable=true)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="apellidos", type="string", length=50, nullable=true)
     */
    private $apellidos;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var int|null
     *
     * @ORM\Column(name="telefono", type="integer", nullable=true)
     */
    private $telefono;

    /**
     * @var string|null
     *
     * @ORM\Column(name="poblacion", type="string", length=255, nullable=true)
     */
    private $poblacion;

    /**
     * @ORM\OneToOne(targetEntity="BolsaDocs", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="doc_id", referencedColumnName="doc_id")
     */
    private $doc;

    /**
     * @ORM\ManyToOne(targetEntity="BolsaBolsas", inversedBy="bolsaSolicitudes", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="bolsa_id", referencedColumnName="bolsa_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bolsa;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigo_postal;


    public function getSolicitudId(): ?int
    {
        return $this->solicitudId;
    }

    public function setSolicitudId(int $solicitudId): self
    {
        $this->solicitudId = $solicitudId;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(?int $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getPoblacion(): ?string
    {
        return $this->poblacion;
    }

    public function setPoblacion(?string $poblacion): self
    {
        $this->poblacion = $poblacion;

        return $this;
    }

    public function getDoc(): ?BolsaDocs
    {
        return $this->doc;
    }

    public function setDoc(?BolsaDocs $doc): self
    {
        $this->doc = $doc;

        return $this;
    }

    public function getBolsa(): ?BolsaBolsas
    {
        return $this->bolsa;
    }

    public function setBolsa(?BolsaBolsas $bolsa): self
    {
        $this->bolsa = $bolsa;

        return $this;
    }

    public function getCodigoPostl(): ?int
    {
        return $this->codigo_postal;
    }

    public function setCodigoPostal(int $codigo_postal): self
    {
        $this->codigo_postal = $codigo_postal;

        return $this;
    }
}
