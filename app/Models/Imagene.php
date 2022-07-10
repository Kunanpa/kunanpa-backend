<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagene extends Model
{
    use HasFactory;

    protected $fillable = [
        'urlImagen',
        'idFlor'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
