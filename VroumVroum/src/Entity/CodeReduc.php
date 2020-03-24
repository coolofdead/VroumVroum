<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CodeReducRepository")
 */
class CodeReduc
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
    private $Value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date_Exp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->Value;
    }

    public function setValue(float $Value): self
    {
        $this->Value = $Value;

        return $this;
    }

    public function getDateExp(): ?\DateTimeInterface
    {
        return $this->Date_Exp;
    }

    public function setDateExp(\DateTimeInterface $Date_Exp): self
    {
        $this->Date_Exp = $Date_Exp;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(string $Code): self
    {
        $this->Code = $Code;

        return $this;
    }
}
