<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargos extends Model
{
    use HasFactory;
    protected $fillable=[
        'area',
        'numero',
        'empresa',
        'iduser',
        'nameuser',
        'fecha',
        'fechainicio',
        'modo',
        'cantidad',
        'pagado',
        'pertence',
        'estado'];
}
