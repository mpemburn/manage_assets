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
        $attributes = Service::factory()->make()->getAttributes();

        $response = $this->post('/api/service/create', $attributes);

        $response->assertStatus(200);

        $this->assertDatabaseHas((new Service())->getTable(), $attributes);
    }

    public function test_create_service_fails_on_missing_data(): void
    {
        $attributes = Service::factory()->make()->getAttributes();

        collect(['name', 'username', 'password'])
            ->each(function ($item) use ($attributes) {
                $attributes[$item] = null;
                $response = $this->post('/api/service/create', $attributes);
                $response->assertStatus(400);

                $this->assertDatabaseMissing((new Service())->getTable(), $attributes);
            });
    }
}
