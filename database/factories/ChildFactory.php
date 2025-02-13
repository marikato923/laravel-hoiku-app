<?php

namespace Database\Factories;

use App\Models\Child;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChildFactory extends Factory
{
    protected $model = Child::class;

    public function definition()
    {
        return [
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'last_kana_name' => 'タナカ', 
            'first_kana_name' => 'タロウ',
            'birthdate' => $this->faker->date('Y-m-d', '-5 years'), 
            'admission_date' => $this->faker->date('Y-m-d', '-5 years'), 
            'medical_history' => null,
            'has_allergy' => false,
            'allergy_type' => null,
            'user_id' => null, 
            'classroom_id' => null, 
            'status' => 'approved',
            'img' => null,
        ];
    }
}
