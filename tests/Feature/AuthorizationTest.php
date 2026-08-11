<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_cannot_access_admin_routes(): void
    {
        $cliente = User::factory()->create(['role' => 'cliente']);

        $this->actingAs($cliente)->get('/admin/users')->assertForbidden();
        $this->actingAs($cliente)->get('/admin/clients')->assertForbidden();
    }

    public function test_staff_cannot_access_user_management(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $this->actingAs($staff)->get('/admin/users')->assertForbidden();
        $this->actingAs($staff)->get('/admin/clients/create')->assertForbidden();
    }

    public function test_staff_can_only_update_their_own_appointments(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $employee = Employee::factory()->create(['user_id' => $staff->id]);

        $otherStaff = User::factory()->create(['role' => 'staff']);
        $otherEmployee = Employee::factory()->create(['user_id' => $otherStaff->id]);

        $client = Client::factory()->create();
        $service = Service::factory()->create();

        $appointment = Appointment::create([
            'client_id' => $client->id,
            'employee_id' => $otherEmployee->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'status' => 'pending',
        ]);

        $this->actingAs($staff)
            ->put('/staff/appointments/'.$appointment->id, [
                'client_id' => $client->id,
                'employee_id' => $otherEmployee->id,
                'service_id' => $service->id,
                'appointment_date' => now()->addDay()->toDateString(),
                'start_time' => '10:00',
                'status' => 'confirmed',
            ])
            ->assertForbidden();
    }

    public function test_cliente_cannot_view_other_clients_appointments(): void
    {
        $cliente = User::factory()->create(['role' => 'cliente']);
        $client = Client::factory()->create(['user_id' => $cliente->id]);

        $otherClient = Client::factory()->create();
        $employee = Employee::factory()->create();
        $service = Service::factory()->create();

        $appointment = Appointment::create([
            'client_id' => $otherClient->id,
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'status' => 'pending',
        ]);

        $this->actingAs($cliente)
            ->get('/cliente/appointments/'.$appointment->id.'/pdf')
            ->assertForbidden();
    }
}
