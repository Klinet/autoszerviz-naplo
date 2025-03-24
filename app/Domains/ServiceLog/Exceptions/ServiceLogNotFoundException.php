<?php

namespace App\Domains\ServiceLog\Exceptions;

class ServiceLogNotFoundException
{

    public function __construct()
    {
        parent::__construct('ServiceLog not found');
    }
}
