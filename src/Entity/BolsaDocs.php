<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BolsaDocs
 *
 * @ORM\Table(name="bolsa_docs")
 * @ORM\Entity
 */
class BolsaDocs 
{
    /**
     * @var int
     *
     * @ORM\Column(name="doc_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $docId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;


    public function getDocId(): ?int
    {
        return $this->docId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
