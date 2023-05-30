<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BolsaPuestos
 *
 * @ORM\Table(name="bolsa_puestos")
 * @ORM\Entity
 */
class BolsaPuestos
{
    /**
     * @var int
     *
     * @ORM\Column(name="puesto_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $puestoId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="puesto", type="string", length=255, nullable=true)
     */
    private $puesto;

    public function getPuestoId(): ?int
    {
        return $this->puestoId;
    }
    public function getPuesto(): ?string
    {
        return $this->puesto;
    }

    public function setPuesto(?string $puesto): self
    {
        $this->puesto = $puesto;

        return $this;
    }

    public function __toString()
    {
        return $this->puesto;
    }
}
