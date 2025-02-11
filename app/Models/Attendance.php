<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


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
        'pickup_time',
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
        'departure_time' => 'datetime',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function shouldSendReminder()
    {
        return $this->pickup_time && Carbon::parse($this->pickup_time)->diffInMinutes(now()) <= 60;
    }
}
