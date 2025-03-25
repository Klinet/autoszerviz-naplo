<?php

namespace App\Domains\Owner\Models;

use App\Domains\Car\Models\Car;
use App\Domains\Client\Models\Client;
use App\Domains\ServiceLog\Models\ServiceLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Client // VAGY ez extendálhatna a Clientből vagy Userből mert akkor lehet pl Üzembentartó is
{

    use HasFactory;

    protected $fillable = [
        'name',
        'idcard',
    ];

    protected $hidden = [];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'client_id');
    }

    public function serviceLogs(): HasMany
    {
        return $this->hasMany(ServiceLog::class, 'client_id');
    }
}
