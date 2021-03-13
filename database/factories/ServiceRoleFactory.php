<?php

namespace Database\Factories;

use App\Models\RoleUi;
use App\Models\Service;
use App\Models\ServiceRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceRoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceRole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'role_id' => RoleUi::factory()
        ];
    }
}
