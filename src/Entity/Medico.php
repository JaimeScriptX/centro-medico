<?php

namespace App\Entity;

use App\Repository\MedicoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MedicoRepository::class)
 */
class Medico
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $papellido;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sapellido;

    /**
     * @ORM\ManyToMany(targetEntity=Especialidades::class, inversedBy="medicos")
     */
    private $especialidades;

    public function __construct()
    {
        $this->especialidades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPapellido(): ?string
    {
        return $this->papellido;
    }

    public function setPapellido(string $papellido): self
    {
        $this->papellido = $papellido;

        return $this;
    }

    public function getSapellido(): ?string
    {
        return $this->sapellido;
    }

    public function setSapellido(string $sapellido): self
    {
        $this->sapellido = $sapellido;

        return $this;
    }

    /**
     * @return Collection<int, especialidades>
     */
    public function getEspecialidades(): Collection
    {
        return $this->especialidades;
    }

    public function addEspecialidad(especialidades $especialidade): self
    {
        if (!$this->especialidades->contains($especialidade)) {
            $this->especialidades[] = $especialidade;
        }

        return $this;
    }

    public function removeEspecialidad(especialidades $especialidade): self
    {
        $this->especialidades->removeElement($especialidade);

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
