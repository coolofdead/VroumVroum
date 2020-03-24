<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRepository")
 */
class Restaurant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Longitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Latitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongitude(): ?float
    {
        return $this->Longitude;
    }

    public function setLongitude(?float $Longitude): self
    {
        $this->Longitude = $Longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->Latitude;
    }

    public function setLatitude(?float $Latitude): self
    {
        $this->Latitude = $Latitude;

        return $this;
    }
}
