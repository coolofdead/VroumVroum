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
    private $prix;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Commande", mappedBy="detail", cascade={"persist", "remove"})
     */
    private $commande;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Menu")
     */
    private $menus;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Plat")
     */
    private $plats;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quantite", mappedBy="commandeDetail")
     */
    private $quantites;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
        $this->plats = new ArrayCollection();
        $this->quantites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

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
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->contains($menu)) {
            $this->menus->removeElement($menu);
        }

        return $this;
    }

    /**
     * @return Collection|Plat[]
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): self
    {
        if (!$this->plats->contains($plat)) {
            $this->plats[] = $plat;
        }

        return $this;
    }

    public function removePlat(Plat $plat): self
    {
        if ($this->plats->contains($plat)) {
            $this->plats->removeElement($plat);
        }

        return $this;
    }

    /**
     * @return Collection|Quantite[]
     */
    public function getQuantites(): Collection
    {
        return $this->quantites;
    }

    public function addQuantite(Quantite $quantite): self
    {
        if (!$this->quantites->contains($quantite)) {
            $this->quantites[] = $quantite;
            $quantite->setCommandeDetail($this);
        }

        return $this;
    }

    public function removeQuantite(Quantite $quantite): self
    {
        if ($this->quantites->contains($quantite)) {
            $this->quantites->removeElement($quantite);
            // set the owning side to null (unless already changed)
            if ($quantite->getCommandeDetail() === $this) {
                $quantite->setCommandeDetail(null);
            }
        }

        return $this;
    }
}
