<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class registropago extends Model
{
    use HasFactory;
    protected $fillable=[
        'idsucursal',
        'sucursal',
        'idoperativo',
        'nombrecliente',
        'cantidad',
        'monto',
        'iduser',
        'responsable',
        'idcliente',
        'fecha',
        'modo',
        'motivo',
        'estado'];
}
