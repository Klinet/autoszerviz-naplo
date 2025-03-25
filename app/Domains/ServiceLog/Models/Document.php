<?php

namespace App\Domains\ServiceLog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'filepath',
        'uploaded_at',
        'description',
    ];

    public function serviceLog(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceLog::class);
    }

    public function serviceLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceLog::class);
    }
}
