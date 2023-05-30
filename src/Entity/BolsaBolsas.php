<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BolsaBolsas
 *
 * @ORM\Table(name="bolsa_bolsas", indexes={@ORM\Index(name="fk_bolsa_bolsas_bolsa_puestos_idx", columns={"puesto_id"})})
 * @ORM\Entity
 */
class BolsaBolsas
{
    /**
     * @var int
     *
     * @ORM\Column(name="bolsa_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $bolsaId;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="inicio", type="date", nullable=true)
     */
    private $inicio;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fin", type="date", nullable=true)
     */
    private $fin;

    /**
     * @var \BolsaPuestos|null
     *
     * @ORM\ManyToOne(targetEntity="BolsaPuestos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="puesto_id", referencedColumnName="puesto_id")
     * })
     */
    private $puesto;

    /**
     * @ORM\OneToMany(targetEntity=BolsaSolicitudes::class, mappedBy="bolsa")
     */
    private $bolsaSolicitudes;

    public function __construct()
    {
        $this->bolsaSolicitudes = new ArrayCollection();
    }


    public function getBolsaId(): ?int
    {
        return $this->bolsaId;
    }

    public function setBolsaId(int $bolsaId): self
    {
        $this->bolsaId = $bolsaId;

        return $this;
    }

    public function getInicio(): ?\DateTimeInterface
    {
        return $this->inicio;
    }

    public function setInicio(?\DateTimeInterface $inicio): self
    {
        $this->inicio = $inicio;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(?\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getPuesto(): ?BolsaPuestos
    {
        return $this->puesto;
    }

    public function setPuesto(?BolsaPuestos $puesto): self
    {
        $this->puesto = $puesto;

        return $this;
    }

    /**
     * @return Collection<int, BolsaSolicitudes>
     */
    public function getBolsaSolicitudes(): Collection
    {
        return $this->bolsaSolicitudes;
    }

    public function addBolsaSolicitude(BolsaSolicitudes $bolsaSolicitude): self
    {
        if (!$this->bolsaSolicitudes->contains($bolsaSolicitude)) {
            $this->bolsaSolicitudes[] = $bolsaSolicitude;
            $bolsaSolicitude->setBolsa($this);
        }

        return $this;
    }

    public function removeBolsaSolicitude(BolsaSolicitudes $bolsaSolicitude): self
    {
        if ($this->bolsaSolicitudes->removeElement($bolsaSolicitude)) {
            // set the owning side to null (unless already changed)
            if ($bolsaSolicitude->getBolsa() === $this) {
                $bolsaSolicitude->setBolsa(null);
            }
        }

        return $this;
    }

}
