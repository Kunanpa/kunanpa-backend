<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres',
        'numTelefono',
        'direccion',
        'distrito',
        'codigoPostal',
        'pais',
        'nota'
    ];

    protected $hidden = [
        'idCliente',
        'created_at',
        'updated_at'
    ];
}
