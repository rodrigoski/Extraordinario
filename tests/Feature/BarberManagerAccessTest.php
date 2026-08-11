<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarberManagerAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_section(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@barbermanager.test',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_staff_cannot_access_user_management(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'email' => 'staff@barbermanager.test',
        ]);

        $response = $this->actingAs($staff)->get('/admin/users');

        $response->assertStatus(403);
    }
}
