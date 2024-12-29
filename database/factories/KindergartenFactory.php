<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kindergarten>
 */
class KindergartenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'テスト',
            'phone_number' => '00000000000',
            'postal_code' => '1111111',
            'address' => 'テスト',
            'principal' => 'テスト',
            'establishment_date' => '1977-01-01',
            'number_of_employees' => 'テスト',
        ];
    }
}
