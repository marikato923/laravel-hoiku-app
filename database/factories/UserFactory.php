<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'last_kana_name' => 'タナカ', 
            'first_kana_name' => 'タロウ',
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password123'), 
            'phone_number' => $this->faker->numerify('080#######'),
            'postal_code' => $this->faker->numerify('1234567'), 
            'address' => $this->faker->address,
            'push_subscription' => json_encode([]), 
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
