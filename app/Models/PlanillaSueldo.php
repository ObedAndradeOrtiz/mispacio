<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanillaSueldo extends Model
{
    use HasFactory;
    protected $fillable = [
        'idplanilla',
        'idusuario',
        'nombre',
        'fecha',
        'descripcion',
        'idcargo',
        'idsucursal',
        'haberbasico',
        'sueldohora',
        'horasdias',
        'diastrabajados',
        'horasextras',
        'bonos',
        'anticipo',
        'pagado',
        'responsable'
    ];
}
