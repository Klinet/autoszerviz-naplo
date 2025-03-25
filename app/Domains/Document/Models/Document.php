<?php

namespace App\Domains\Document\Models;

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
}
