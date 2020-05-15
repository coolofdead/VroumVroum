<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuantiteRepository")
 */
class Quantite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CommandeDetail", inversedBy="quantites")
     */
    private $commandeDetail;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plat")
     */
    private $plat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(?int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCommandeDetail(): ?CommandeDetail
    {
        return $this->commandeDetail;
    }

    public function setCommandeDetail(?CommandeDetail $commandeDetail): self
    {
        $this->commandeDetail = $commandeDetail;

        return $this;
    }

    public function getPlat(): ?Plat
    {
        return $this->plat;
    }

    public function setPlat(?Plat $plat): self
    {
        $this->plat = $plat;

        return $this;
    }
}
