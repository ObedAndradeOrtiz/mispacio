<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    use HasFactory;
    protected $fillable=[
        'area',
        'numero',
        'empresa',
        'iduser',
        'nameuser',
        'namebeneficiario',
        'fecha',
        'fechainicio',
        'fechapagado',
        'modo',
        'cantidad',
        'pagado',
        'pertence',
        'estado',
        'comentario',
        'rutaarchivo'];
}
