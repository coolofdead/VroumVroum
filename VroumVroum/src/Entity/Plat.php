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
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Restaurant", inversedBy="plats")
     */
    private $restaurant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CategoriePlat", inversedBy="plats")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePlat", inversedBy="plats")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Menu", mappedBy="plats")
     */
    private $menus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $urlImg;

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
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getCategorie(): ?CategoriePlat
    {
        return $this->categorie;
    }

    public function setCategorie(?CategoriePlat $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getType(): ?TypePlat
    {
        return $this->type;
    }

    public function setType(?TypePlat $type): self
    {
        $this->type = $type;

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
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUrlImg(): ?string
    {
        return $this->urlImg;
    }

    public function setUrlImg(?string $urlImg): self
    {
        $this->urlImg = $urlImg;

        return $this;
    }
}
