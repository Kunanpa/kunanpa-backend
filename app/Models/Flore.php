<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flore extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'detalles',
        'precioFinal',
        'descuento',
        'precioInicial',
        'stock'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
