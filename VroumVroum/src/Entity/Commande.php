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
    private $detail;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Menu")
     */
    private $menu;
//    deja dans le commande detail a remove si non utilisé
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CodeReduc", inversedBy="no")
     */
    private $codeReduc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commandes")
     */
    private $membre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Restaurant", inversedBy="commandes")
     */
    private $restaurant;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Status", cascade={"persist", "remove"})
     */
    private $status;

    public function __construct()
    {
        $this->menu = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetail(): ?Commandedetail
    {
        return $this->detail;
    }

    public function setDetail(?Commandedetail $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * @return Collection|menu[]
     */
    public function getMenu(): Collection
    {
        return $this->menu;
    }

    public function addMenu(menu $menu): self
    {
        if (!$this->menu->contains($menu)) {
            $this->menu[] = $menu;
        }

        return $this;
    }

    public function removeMenu(menu $menu): self
    {
        if ($this->menu->contains($menu)) {
            $this->menu->removeElement($menu);
        }

        return $this;
    }

    public function getCodeReduc(): ?Codereduc
    {
        return $this->codeReduc;
    }

    public function setCodeReduc(?Codereduc $codeReduc): self
    {
        $this->codeReduc = $codeReduc;

        return $this;
    }

    public function getMembre(): ?User
    {
        return $this->membre;
    }

    public function setMembre(?User $membre): self
    {
        $this->membre = $membre;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }
}
