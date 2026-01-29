<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tablas extends Model
{
    use HasFactory;
    protected $fillable=[
        'Descripcion',
        'Code',
        'CapMax',
         'Mts2',
         'Piso',
         'Descripcion',
         'Tipo',
        'Edificio',
         'Seccion',
         'Longitud',
         'Latitud',
         'Ciudad',
         'Recursos',
         'Estado'];
}
