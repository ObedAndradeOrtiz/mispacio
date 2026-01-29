<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    use HasFactory;
    protected $fillable=[
    'area',
    'empresa',
    'fecha',
    'hora',
    'telefono',
    'responsable',
    'cantidadtotal',
    'ingreso',
     'cantidadaregistrar',
     'encargado',
     'comentario',
    'estado'];
}
