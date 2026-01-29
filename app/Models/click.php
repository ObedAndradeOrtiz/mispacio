<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class click extends Model
{
    use HasFactory;
    protected $fillable=[
        'idweb',
        'idenlace',
        'idproducto',
        'click',
        'tipo',
        'fecha'];
}
