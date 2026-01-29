<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class registroinventario extends Model
{
    use HasFactory;
    protected $fillable=[
        'idsucursal',
        'sucursal',
        'idproducto',
        'nombreproducto',
        'cantidad',
        'precio',
        'iduser',
        'idcliente',
        'fecha',
        'modo',
        'motivo',
        'estado'];
}
