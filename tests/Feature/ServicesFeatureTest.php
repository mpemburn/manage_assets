<?php

namespace Tests\Feature;

use App\Models\Service;
use Database\Factories\ServiceFactory;
use Faker\Factory;
use Faker\Generator;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ServicesFeatureTest extends TestCase
{
    protected Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();

        $this->refreshDatabase();
    }

    public function test_user_can_create_new_service(): void
    {
        $attributes = [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'username' => $this->faker->email,
            'password' => $this->faker->password(8) . $this->faker->numberBetween(0, 20),
            'notes' => $this->faker->paragraph
        ];
        $response = $this->post('/api/service/create', $attributes);

        $response->assertStatus(200);

        $this->assertDatabaseHas((new Service())->getTable(), $attributes);
    }

    public function test_create_service_fails_on_missing_data(): void
    {
        $attributes = [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'username' => $this->faker->email,
            'password' => $this->faker->password(8) . $this->faker->numberBetween(0, 20),
            'notes' => $this->faker->paragraph
        ];

        collect(['name', 'username', 'password'])
            ->each(function ($item) use ($attributes) {
                $attributes[$item] = null;
                $response = $this->post('/api/service/create', $attributes);
                $response->assertStatus(400);

                $this->assertDatabaseMissing((new Service())->getTable(), $attributes);
            });
    }
}
