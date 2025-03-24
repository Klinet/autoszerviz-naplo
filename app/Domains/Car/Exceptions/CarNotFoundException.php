<?php

namespace App\Domains\Car\Exceptions;

class CarNotFoundException
{

    public function __construct()
    {
        parent::__construct('Car not found');
    }
}
