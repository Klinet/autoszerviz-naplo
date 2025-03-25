<?php

namespace App\Domains\ServiceLog\Models;

use App\Domains\Car\Models\Car;
use App\Domains\Owner\Models\Owner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceLog extends Model
{
    use HasFactory;

    // ?? - document_id:nullable (munkalap azonosítója)

    protected $table = 'services'; // Fontos: a tábla neve a feladatleírás szerint
    // de class ServiceLog lett mert az OOP réteg Service elég foglalt

    protected $fillable = [
        'client_id',
        'car_id',
        'lognumber',
        'event_id',
        'eventtime',
        'document_id',
    ];
    protected $casts = [
        'eventtime'  => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Owner::class, 'client_id');
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }

    public function document(): BelongsTo
    {
        // ez a kulcs csak a json-ok alapján következtethető és így a document tbl is!
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function getCasts(): array
    {
        return $this->casts;
    }

    public function getEventNameAttribute()
    {
        return config("event_types.types.{$this->event}.name");
    }
}
