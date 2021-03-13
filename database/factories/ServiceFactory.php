<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'username' => $this->faker->email,
            // Generate 10 character password with upper and lower case plus number
            'password' => $this->faker->regexify('[A-Z]{2}[a-z]{6}[0-9]{2}'),
            'notes' => $this->faker->paragraph
        ];
    }
}
