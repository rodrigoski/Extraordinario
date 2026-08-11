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

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_requires_client_employee_and_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post('/admin/appointments', [])
            ->assertSessionHasErrors(['client_id', 'employee_id', 'service_id', 'appointment_date', 'start_time']);
    }

    public function test_appointment_can_be_created(): void
    {
        Storage::fake('public');
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();
        $employee = Employee::factory()->create();
        $service = Service::factory()->create(['duration' => 45]);

        $response = $this->actingAs($admin)->post('/admin/appointments', [
            'client_id' => $client->id,
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00',
            'notes' => 'Cita de prueba',
        ]);

        $response->assertRedirect(route('admin.appointments.index'));
        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
            'employee_id' => $employee->id,
            'service_id' => $service->id,
        ]);
    }

    public function test_employee_cannot_have_two_overlapping_appointments(): void
    {
        Storage::fake('public');
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();
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

        $this->actingAs($admin)
            ->post('/admin/appointments', [
                'client_id' => $client->id,
                'employee_id' => $employee->id,
                'service_id' => $service->id,
                'appointment_date' => now()->addDay()->toDateString(),
                'start_time' => '10:30',
            ])
            ->assertSessionHasErrors('start_time');
    }

    public function test_pdf_and_confirmation_email_are_generated_on_create(): void
    {
        Storage::fake('public');
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();
        $employee = Employee::factory()->create();
        $service = Service::factory()->create(['duration' => 30]);

        $this->actingAs($admin)->post('/admin/appointments', [
            'client_id' => $client->id,
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '12:00',
        ]);

        $appointment = Appointment::first();
        $this->assertNotNull($appointment->pdf_path);
        Storage::disk('public')->assertExists($appointment->pdf_path);
        Mail::assertQueued(AppointmentConfirmationMail::class);
    }
}
