<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceRole;
use App\Models\ServiceSecurityQuestion;
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
            'password' => $this->faker->regexify('[A-Z]{2}[a-z]{6}[0-9]{2}'),
            'notes' => $this->faker->paragraph
        ])->each(function ($value, $key) use ($attributes, $serviceId) {
            // Change the value of the field
            $attributes[$key] = $value;
            $response = $this->put('/api/service/update/' . $serviceId, $attributes);
            $response->assertStatus(200);

            $this->assertDatabaseHas((new Service())->getTable(), $attributes);
        });
    }

    public function test_user_can_delete_service(): void
    {
        $service = Service::factory()->createOne();
        $attributes = $service->getAttributes();
        $serviceId = $attributes['id'];

        $response = $this->delete('/api/service/delete/' . $serviceId, $attributes);
        $response->assertStatus(200);

        $this->assertDatabaseMissing((new Service())->getTable(), $attributes);
    }

    public function test_fail_to_delete_service(): void
    {
        $service = Service::factory()->createOne();
        $attributes = $service->getAttributes();
        // Change the id to a fairly impossible value
        $serviceId = 999999999999;

        $response = $this->delete('/api/service/delete/' . $serviceId, $attributes);
        $response->assertStatus(400);

        // Item wasn't deleted, so should still be in the database.
        $this->assertDatabaseHas((new Service())->getTable(), $attributes);
    }

    public function test_can_add_service_security_question(): void
    {
        $securityQuestion = ServiceSecurityQuestion::factory()->createOne();
        $attributes = $securityQuestion->getAttributes();
        $this->assertDatabaseHas((new ServiceSecurityQuestion())->getTable(), $attributes);
    }

    public function test_can_add_service_role(): void
    {
        $serviceRole = ServiceRole::factory()->createOne();
        $attributes = $serviceRole->getAttributes();
        $this->assertDatabaseHas((new ServiceRole())->getTable(), $attributes);
    }
}
