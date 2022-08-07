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
        'estado'
    ];

    protected $hidden = [
        'idCompra',
        'idFlor',
        'created_at',
        'updated_at'
    ];
}
