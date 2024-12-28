<?php

namespace Database\Factories;

use Faker\Generator as Faker;
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
        $faker = \Faker\Factory::create('ja_JP');

        $lastNameKana = mb_convert_kana($faker->lastName(), 'K');
        $firstNameKana = mb_convert_kana($faker->firstName(), 'K');

        return [
            'last_name' => $this->faker->lastName(),         
            'first_name' => $this->faker->firstName(),        
            'last_kana_name' => $lastNameKana,  
            'first_kana_name' => $firstNameKana,
            'birthdate' => $this->faker->date(),
            'img' => '',
            'admission_date' => $this->faker->date(),
            'medical_history' => $this->faker->text(), 
            'has_allergy' => $this->faker->boolean(), 
            'allergy_type' => $this->faker->boolean() ? $this->faker->word() :null,
        ];
    }
}
