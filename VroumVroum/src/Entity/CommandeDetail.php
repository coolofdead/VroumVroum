<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeDetailRepository")
 */
class CommandeDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Commande", mappedBy="Detail", cascade={"persist", "remove"})
     */
    private $commande;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Menu")
     */
    private $Menus;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Plat")
     */
    private $Plats;

    public function __construct()
    {
        $this->Menus = new ArrayCollection();
        $this->Plats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        // set (or unset) the owning side of the relation if necessary
        $newDetail = null === $commande ? null : $this;
        if ($commande->getDetail() !== $newDetail) {
            $commande->setDetail($newDetail);
        }

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->Menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->Menus->contains($menu)) {
            $this->Menus[] = $menu;
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->Menus->contains($menu)) {
            $this->Menus->removeElement($menu);
        }

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
