<?php

namespace App\Utils;

use App\Entity\Advert;

interface IPriceEstimation {

    public function EstimateCar(Advert $carType) : int;

}