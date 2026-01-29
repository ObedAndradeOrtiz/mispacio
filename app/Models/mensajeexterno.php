<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mensajeexterno extends Model
{
    use HasFactory;
    protected $fillable=[
    'tema',
    'nombre',
    'email',
    'telefono',
    'mensaje',
    'fecha'
     ];
}
