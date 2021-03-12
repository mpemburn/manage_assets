<?php

namespace Tests\Feature;

use App\Models\Service;
use Faker\Factory;
use Faker\Generator;
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
        $attributes = Service::factory()->make()->getAttributes();

        $response = $this->post('/api/service/create', $attributes);

        $response->assertStatus(200);

        $this->assertDatabaseHas((new Service())->getTable(), $attributes);
    }

    public function test_create_service_fails_on_missing_data(): void
    {
        $attributes = Service::factory()->make()->getAttributes();

        // These are all of the required (non-null) fields
        collect(['name', 'username', 'password'])
            ->each(function ($item) use ($attributes) {
                // Drop an item from the attributes
                $attributes[$item] = null;
                $response = $this->post('/api/service/create', $attributes);
                $response->assertStatus(400);

                $this->assertDatabaseMissing((new Service())->getTable(), $attributes);
            });
    }

    public function test_user_can_update_service(): void
    {
        $service = Service::factory()->createOne();
        $attributes = $service->getAttributes();
        $serviceId = $attributes['id'];
        $this->assertDatabaseHas((new Service())->getTable(), $attributes);

        // We can't pass along the name without getting an error
        unset($attributes['name']);
        // Iterate of all remaining fields to make sure they can be changed
        collect([
            'description' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'username' => $this->faker->email,
            'password' => $this->faker->password(8) . $this->faker->numberBetween(0, 20),
            'notes' => $this->faker->paragraph
        ])->each(function ($value, $key) use ($attributes, $serviceId) {
            // Change the value of the field
            $attributes[$key] = $value;
            $response = $this->put('/api/service/update/' . $serviceId, $attributes);
            $response->assertStatus(200);

            $this->assertDatabaseHas((new Service())->getTable(), $attributes);
        });
    }
}
