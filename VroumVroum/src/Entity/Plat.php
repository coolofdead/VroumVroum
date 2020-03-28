<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlatRepository")
 */
class Plat
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Restaurant", inversedBy="plats")
     */
    private $Restaurant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CategoriePlat", inversedBy="plats")
     */
    private $Categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePlat", inversedBy="plats")
     */
    private $Type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Menu", mappedBy="Plats")
     */
    private $menus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
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

    public function getRestaurant(): ?Restaurant
    {
        return $this->Restaurant;
    }

    public function setRestaurant(?Restaurant $Restaurant): self
    {
        $this->Restaurant = $Restaurant;

        return $this;
    }

    public function getCategorie(): ?CategoriePlat
    {
        return $this->Categorie;
    }

    public function setCategorie(?CategoriePlat $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getType(): ?TypePlat
    {
        return $this->Type;
    }

    public function setType(?TypePlat $Type): self
    {
        $this->Type = $Type;

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
            $menu->addPlat($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->contains($menu)) {
            $this->menus->removeElement($menu);
            $menu->removePlat($this);
        }

        return $this;
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
}
