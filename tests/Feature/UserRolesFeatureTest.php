<?php

namespace Tests\Feature;

use Database\Seeders\MemberSeeder;
use Faker\Factory;
use Faker\Generator;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class UserRolesFeatureTest extends TestCase
{
    protected Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();

        $this->refreshDatabase();
    }

    public function test_can_create_new_role(): void
    {
        $attributes = [
            'name' => $this->faker->word
        ];

        $response = $this->post('/api/roles/create', $attributes);

        $response->assertStatus(200);

        $this->assertDatabaseHas((new Role())->getTable(), $attributes);
    }

    public function test_can_update_role(): void
    {
        $attributes = [
            'name' => $this->faker->word
        ];
        $response = $this->post('/api/roles/create', $attributes);
        $response->assertStatus(200);
        $roleId = $response->json('id');

        $attributes['id'] = $roleId;
        $this->assertDatabaseHas((new Role())->getTable(), $attributes);

        $newAttributes = [
            'id' => $roleId,
            'name' => $this->faker->word
        ];

        $response = $this->put('/api/roles/update', $newAttributes);
        $response->assertStatus(200);

        $this->assertDatabaseHas((new Role())->getTable(), $newAttributes);
    }

    public function test_can_delete_role(): void
    {
        $attributes = [
            'name' => $this->faker->word
        ];
        $response = $this->post('/api/roles/create', $attributes);
        $response->assertStatus(200);
        $roleId = $response->json('id');
        $attributes['id'] = $roleId;

        $this->assertDatabaseHas((new Role())->getTable(), $attributes);

        $response = $this->delete('/api/roles/delete', $attributes);
        $response->assertStatus(200);

        $this->assertDatabaseMissing((new Role())->getTable(), $attributes);
    }
}
