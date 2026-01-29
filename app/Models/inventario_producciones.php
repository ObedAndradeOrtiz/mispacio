<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventario_producciones extends Model
{
    use HasFactory;
    protected $table = 'inventario_producciones';
    protected $fillable = ['producto_id', 'fecha', 'cantidad'];
    protected $casts = ['fecha' => 'date'];
}