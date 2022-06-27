<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'avatar',
        'dni',
        'direccion'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
