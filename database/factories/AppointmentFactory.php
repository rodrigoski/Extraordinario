<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'employee_id' => Employee::factory(),
            'service_id' => Service::factory(),
            'appointment_date' => fake()->dateTimeBetween('today', '+2 weeks')->format('Y-m-d'),
            'start_time' => fake()->randomElement(['09:00:00', '10:00:00', '11:00:00', '12:00:00']),
            'end_time' => '10:45:00',
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'notes' => fake()->sentence(),
            'pdf_path' => null,
        ];
    }
}
