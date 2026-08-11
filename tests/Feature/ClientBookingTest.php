<?php

namespace Tests\Feature;

use App\Mail\AppointmentConfirmationMail;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClientBookingTest extends TestCase
{
    use RefreshDatabase;

    private function clienteConPerfil(): array
    {
        $user = User::factory()->create(['role' => 'cliente']);
        $client = Client::factory()->create(['user_id' => $user->id]);

        return [$user, $client];
    }

    public function test_cliente_can_view_booking_form(): void
    {
        [$user] = $this->clienteConPerfil();

        $this->actingAs($user)
            ->get('/cliente/appointments/create')
            ->assertOk();
    }

    public function test_cliente_can_book_an_appointment(): void
    {
        Storage::fake('public');
        Mail::fake();

        [$user, $client] = $this->clienteConPerfil();
        $employee = Employee::factory()->create();
        $service = Service::factory()->create(['duration' => 45]);

        $response = $this->actingAs($user)->post('/cliente/appointments', [
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '11:00',
            'notes' => 'Reserva desde el portal.',
        ]);

        $response->assertRedirect(route('cliente.appointments'));
        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'status' => 'pending',
        ]);
        Mail::assertQueued(AppointmentConfirmationMail::class);
    }

    public function test_cliente_cannot_book_for_another_client(): void
    {
        Storage::fake('public');
        Mail::fake();

        [$user, $client] = $this->clienteConPerfil();
        $otherClient = Client::factory()->create();
        $employee = Employee::factory()->create();
        $service = Service::factory()->create(['duration' => 30]);

        $this->actingAs($user)->post('/cliente/appointments', [
            'client_id' => $otherClient->id,
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '09:00',
        ]);

        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
        ]);
        $this->assertDatabaseMissing('appointments', [
            'client_id' => $otherClient->id,
        ]);
    }

    public function test_cliente_cannot_book_when_employee_has_conflict(): void
    {
        Storage::fake('public');
        Mail::fake();

        [$user, $client] = $this->clienteConPerfil();
        $employee = Employee::factory()->create();
        $service = Service::factory()->create(['duration' => 45]);

        Appointment::create([
            'client_id' => $client->id,
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->post('/cliente/appointments', [
                'employee_id' => $employee->id,
                'service_id' => $service->id,
                'appointment_date' => now()->addDay()->toDateString(),
                'start_time' => '10:30',
            ])
            ->assertSessionHasErrors('start_time');
    }

    public function test_admin_cannot_access_client_booking_flow(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/cliente/appointments/create')
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_on_client_booking(): void
    {
        $this->get('/cliente/appointments/create')->assertRedirect(route('login'));
    }
}
