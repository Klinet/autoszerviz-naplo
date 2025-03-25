<?php

namespace App\Domains\Car\Models;

use App\Domains\Owner\Models\Owner;
use App\Domains\ServiceLog\Models\ServiceLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'car_id',
        'type',
        'registered',
        'ownbrand',
        'accident',
    ];

    protected $casts = [
        'registered' => 'datetime',
    ];
    public function client(): BelongsTo
    {
        return $this->belongsTo(Owner::class, 'client_id');
    }

    public function serviceLogs(): HasMany
    {
        return $this->hasMany(ServiceLog::class, 'car_id','id');
    }
    public function scopeOwnBrand($query)
    {
        return $query->where('ownbrand', 1);
    }
}
