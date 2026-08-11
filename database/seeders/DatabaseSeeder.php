<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUsers();
        $this->seedClients();
        $this->seedEmployees();
        $this->seedServices();
        $this->seedAppointments();
    }

    protected function seedUsers(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@barbermanager.test'],
            [
                'name' => 'Administrador',
                'password' => 'Admin12345!',
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@barbermanager.test'],
            [
                'name' => 'Juan Pérez',
                'password' => 'Staff12345!',
                'role' => 'staff',
            ]
        );

        User::firstOrCreate(
            ['email' => 'cliente@barbermanager.test'],
            [
                'name' => 'Cliente Demo',
                'password' => 'Cliente12345!',
                'role' => 'cliente',
            ]
        );

        // Usuarios adicionales para los demás empleados.
        User::firstOrCreate(
            ['email' => 'staff2@barbermanager.test'],
            [
                'name' => 'María Gómez',
                'password' => 'Staff12345!',
                'role' => 'staff',
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff3@barbermanager.test'],
            [
                'name' => 'Carlos Ruiz',
                'password' => 'Staff12345!',
                'role' => 'staff',
            ]
        );
    }

    protected function seedClients(): void
    {
        $clientRecords = Client::factory()->count(5)->create();
        $clientRecords->prepend(Client::create([
            'user_id' => User::where('email', 'cliente@barbermanager.test')->first()->id,
            'name' => 'Cliente',
            'last_name' => 'Demo',
            'email' => 'cliente@barbermanager.test',
            'phone' => '555000111',
            'address' => 'Calle Principal 123',
            'birth_date' => '1998-04-12',
            'notes' => 'Cliente de prueba',
        ]));
    }

    protected function seedEmployees(): void
    {
        $employees = [
            [
                'user_id' => User::where('email', 'staff@barbermanager.test')->first()->id,
                'name' => 'Juan',
                'last_name' => 'Pérez',
                'phone' => '555222333',
                'position' => 'Barbero Principal',
                'active' => true,
            ],
            [
                'user_id' => User::where('email', 'staff2@barbermanager.test')->first()->id,
                'name' => 'María',
                'last_name' => 'Gómez',
                'phone' => '555222334',
                'position' => 'Estilista',
                'active' => true,
            ],
            [
                'user_id' => User::where('email', 'staff3@barbermanager.test')->first()->id,
                'name' => 'Carlos',
                'last_name' => 'Ruiz',
                'phone' => '555222335',
                'position' => 'Barbero',
                'active' => true,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }

    protected function seedServices(): void
    {
        $services = [
            ['name' => 'Corte clásico', 'description' => 'Corte con tijera y máquina', 'duration' => 45, 'price' => 20.00, 'active' => true],
            ['name' => 'Corte + barba', 'description' => 'Corte completo con perfilado', 'duration' => 60, 'price' => 30.00, 'active' => true],
            ['name' => 'Barba', 'description' => 'Afeitado y diseño', 'duration' => 30, 'price' => 18.00, 'active' => true],
            ['name' => 'Afeitado', 'description' => 'Afeitado con navaja', 'duration' => 25, 'price' => 15.00, 'active' => true],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }

    protected function seedAppointments(): void
    {
        if (Appointment::exists()) {
            return;
        }

        $client = Client::where('email', 'cliente@barbermanager.test')->first();
        $juan = Employee::where('name', 'Juan')->first();
        $maria = Employee::where('name', 'María')->first();

        $corteClasico = Service::where('name', 'Corte clásico')->first();
        $corteBarba = Service::where('name', 'Corte + barba')->first();
        $barba = Service::where('name', 'Barba')->first();
        $afeitado = Service::where('name', 'Afeitado')->first();

        if (! $client || ! $juan || ! $maria) {
            return;
        }

        Appointment::create([
            'client_id' => $client->id,
            'employee_id' => $juan->id,
            'service_id' => $corteClasico->id,
            'appointment_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'status' => 'pending',
            'notes' => 'Cita para hoy',
        ]);

        Appointment::create([
            'client_id' => $client->id,
            'employee_id' => $maria->id,
            'service_id' => $corteBarba->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '11:00:00',
            'end_time' => '12:00:00',
            'status' => 'pending',
            'notes' => 'Cita para mañana (recordatorio)',
        ]);

        Appointment::create([
            'client_id' => $client->id,
            'employee_id' => $juan->id,
            'service_id' => $barba->id,
            'appointment_date' => now()->addDays(3)->toDateString(),
            'start_time' => '16:00:00',
            'end_time' => '16:30:00',
            'status' => 'confirmed',
            'notes' => null,
        ]);

        Appointment::create([
            'client_id' => $client->id,
            'employee_id' => $juan->id,
            'service_id' => $afeitado->id,
            'appointment_date' => now()->subDays(5)->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '09:25:00',
            'status' => 'completed',
            'notes' => 'Historial de servicios',
        ]);
    }
}
