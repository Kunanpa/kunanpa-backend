<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraFlore extends Model
{
    use HasFactory;

    protected $fillable = [
        'cantidad',
        'costo',
        'estado',
        'idCompra',
        'idFlor',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
