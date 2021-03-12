<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceSecurityQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceSecurityQuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceSecurityQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'service _id' => Service::factory(),
            'question' => $this->faker->sentence,
            'answer' => $this->faker->sentence
        ];
    }
}
