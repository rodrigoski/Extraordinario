<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'staff'])->id,
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->phoneNumber(),
            'position' => fake()->randomElement(['Barbero', 'Estilista', 'Recepcionista']),
            'active' => true,
        ];
    }
}
