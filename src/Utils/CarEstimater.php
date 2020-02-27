<?php

namespace App\Utils;

use App\Entity\Advert;
use App\Form\CarType;
use App\Utils\IPriceEstimation;

class CarEstimater implements IPriceEstimation {

    public function EstimateCar(Advert $carType): int
    {
        return abs($carType->getNbKm() * $carType->getNbDays() / $carType->getCarYear());
    }

}