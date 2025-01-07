<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $dates = [
        'created_at',
        'arrival_time',
        'departure_time',
    ];

    protected $fillable = [
        'child_id',
        'arrival_time',
        'departure_time',
        'pickup_name',
        'pickup_time',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }


}
