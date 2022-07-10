<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlorCategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'idFlor',
        'idCategoria'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
