<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calls extends Model
{
    use HasFactory;
    protected $fillable=['area',
    'empresa',
    'fecha',
    'fechacita',
    'hora',
    'telefono',
    'comentario',
    'cantidad',
    'encargado',
    'responsable',
    'estado'];
}
