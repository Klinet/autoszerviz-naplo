<?php

namespace App\Domains\Client\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

abstract class Client extends Model
{
    // Nem kell a HasFactory, mert absztrakt osztály
    // lehet Owner vagy Operatro absztrakció pl.

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'idcard',
    ];

    abstract public function cars(): HasMany;

    abstract public function serviceLogs(): HasMany;
}
