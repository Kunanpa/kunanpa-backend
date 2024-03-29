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
        'stock',
        'idVendedor'
    ];

    protected $hidden = [
        'numVentas',
        'created_at',
        'updated_at'
    ];
}
