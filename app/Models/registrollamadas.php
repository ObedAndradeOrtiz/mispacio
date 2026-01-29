<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class registrollamadas extends Model
{
    use HasFactory;
    protected $fillable=[
        'idllamada',
        'telefono',
        'iduser',
        'responsable',
        'fecha',
        'estado'];
}
