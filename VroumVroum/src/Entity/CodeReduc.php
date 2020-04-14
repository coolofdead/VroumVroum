<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CodeReducRepository")
 */
class CodeReduc
{
    /**
     * @ORM\Id()
     * @ORM\Generatedvalue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_Exp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commande", mappedBy="CodeReduc")
     */
    private $no;

    public function __construct()
    {
        $this->no = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDateExp(): ?DateTimeInterface
    {
        return $this->date_Exp;
    }

    public function setDateExp(DateTimeInterface $date_Exp): self
    {
        $this->date_Exp = $date_Exp;

        return $this;
    }

    public function getcode(): ?string
    {
        return $this->code;
    }

    public function setcode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getNo(): Collection
    {
        return $this->no;
    }

    public function addNo(Commande $no): self
    {
        if (!$this->no->contains($no)) {
            $this->no[] = $no;
            $no->setCodeReduc($this);
        }

        return $this;
    }

    public function removeNo(Commande $no): self
    {
        if ($this->no->contains($no)) {
            $this->no->removeElement($no);
            // set the owning side to null (unless already changed)
            if ($no->getCodeReduc() === $this) {
                $no->setCodeReduc(null);
            }
        }

        return $this;
    }
}
