<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Child;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Child>
 */
class ChildFactory extends Factory
{
    protected $model = Child::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'kana' => $this->faker->kanaName(),
            'birthdate' => $this->faker->date(),
            'img' => '',
            'admission_date' => $this->faker->date(),
            'medical_history' => $this->faker->text(), 
            'has_allergy' => $this->faker->boolean(), 
            'allergy_type' => $this->faker->boolean() ? $this->faker->word() :null,
        ];
    }
}
