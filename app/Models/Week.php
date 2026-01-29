<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date',
        'end_date',
        'name',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function assignments()
    {
        return $this->hasMany(WorkAssignment::class);
    }
}
