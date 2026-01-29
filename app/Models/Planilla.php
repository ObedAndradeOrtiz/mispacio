<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planilla extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha',
        'semana',
        'trabajadores',
        'haberbasico',
        'horasdias',
        'horasextras',
        'bonos',
        'anticipo',
        'pagado',
        'responsable',
        'estado'
    ];
}
