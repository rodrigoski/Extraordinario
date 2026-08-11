<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Corte clásico', 'Corte + barba', 'Barba', 'Afeitado', 'Tinte']),
            'description' => fake()->sentence(),
            'duration' => fake()->randomElement([25, 30, 45, 60]),
            'price' => fake()->randomFloat(2, 10, 50),
            'active' => true,
        ];
    }
}
