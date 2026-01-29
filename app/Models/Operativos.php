<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operativos extends Model
{
    use HasFactory;
    protected $fillable=['area',
    'idempresa',
    'empresa',
    'fecha',
    'hora',
    'telefono',
    'comentario',
    'responsable',
    'encargado',
    'cantidadtotal',
    'ingreso',
     'cantidadaregistrar',
    'estado'];

}
