<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Child;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        return [
            'child_id' => null, 
            'arrival_time' => null, 
            'departure_time' => null, 
            'pickup_time' => null,
        ];
    }
}
