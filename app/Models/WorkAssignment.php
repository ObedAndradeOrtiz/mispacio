<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkAssignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'week_id',
        'area_id',
        'user_id',
        'work_date',
    ];

    protected $dates = [
        'work_date',
    ];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function area()
    {
        return $this->belongsTo(Areas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}