<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CommandeDetail", inversedBy="commande", cascade={"persist", "remove"})
     */
    private $Detail;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Menu")
     */
    private $Menu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\COdereduc", inversedBy="no")
     */
    private $CodeReduc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commandes")
     */
    private $Membre;

    public function __construct()
    {
        $this->Menu = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetail(): ?CommandeDetail
    {
        return $this->Detail;
    }

    public function setDetail(?CommandeDetail $Detail): self
    {
        $this->Detail = $Detail;

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenu(): Collection
    {
        return $this->Menu;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->Menu->contains($menu)) {
            $this->Menu[] = $menu;
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->Menu->contains($menu)) {
            $this->Menu->removeElement($menu);
        }

        return $this;
    }

    public function getCodeReduc(): ?COdereduc
    {
        return $this->CodeReduc;
    }

    public function setCodeReduc(?COdereduc $CodeReduc): self
    {
        $this->CodeReduc = $CodeReduc;

        return $this;
    }

    public function getMembre(): ?User
    {
        return $this->Membre;
    }

    public function setMembre(?User $Membre): self
    {
        $this->Membre = $Membre;

        return $this;
    }
}
