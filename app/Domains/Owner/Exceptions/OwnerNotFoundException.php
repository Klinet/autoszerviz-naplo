<?php

namespace App\Domains\Owner\Exceptions;

class OwnerNotFoundException
{

    public function __construct()
    {
        parent::__construct('Owner not found');
    }
}
