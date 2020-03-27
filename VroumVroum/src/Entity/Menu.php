<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuRepository")
 */
class Menu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Plat", inversedBy="menus")
     */
    private $Plats;

    public function __construct()
    {
        $this->Plats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    /**
     * @return Collection|Plat[]
     */
    public function getPlats(): Collection
    {
        return $this->Plats;
    }

    public function addPlat(Plat $plat): self
    {
        if (!$this->Plats->contains($plat)) {
            $this->Plats[] = $plat;
        }

        return $this;
    }

    public function removePlat(Plat $plat): self
    {
        if ($this->Plats->contains($plat)) {
            $this->Plats->removeElement($plat);
        }

        return $this;
    }
}
