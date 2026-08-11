<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_client(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/clients', [
            'name' => 'Ana',
            'last_name' => 'López',
            'email' => 'ana@example.com',
            'phone' => '5551234567',
        ]);

        $response->assertRedirect(route('admin.clients.index'));
        $this->assertDatabaseHas('clients', ['email' => 'ana@example.com']);
    }

    public function test_client_requires_name_last_name_email_and_phone(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post('/admin/clients', [])
            ->assertSessionHasErrors(['name', 'last_name', 'email', 'phone']);
    }

    public function test_admin_can_update_client(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $this->actingAs($admin)
            ->put('/admin/clients/'.$client->id, [
                'name' => 'Ana',
                'last_name' => 'López',
                'email' => $client->email,
                'phone' => '5559998888',
            ])
            ->assertRedirect(route('admin.clients.index'));

        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'Ana', 'phone' => '5559998888']);
    }

    public function test_phone_must_contain_exactly_10_digits(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post('/admin/clients', [
                'name' => 'Ana',
                'last_name' => 'López',
                'email' => 'ana2@example.com',
                'phone' => '9999',
            ])
            ->assertSessionHasErrors('phone');

        $this->actingAs($admin)
            ->post('/admin/clients', [
                'name' => 'Ana',
                'last_name' => 'López',
                'email' => 'ana3@example.com',
                'phone' => '55 1234-5678',
            ])
            ->assertSessionHasNoErrors();
    }

    public function test_client_can_be_soft_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();

        $this->actingAs($admin)
            ->delete('/admin/clients/'.$client->id)
            ->assertRedirect(route('admin.clients.index'));

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
        $this->assertDatabaseHas('clients', ['id' => $client->id]);
    }

    public function test_staff_cannot_create_clients(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $this->actingAs($staff)
            ->post('/admin/clients', [])
            ->assertForbidden();
    }
}
