<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_client_panel(): void
    {
        $response = $this->get('/client');
        $response->assertRedirect(route('login'));
    }

    public function test_client_cannot_access_admin_panel(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_client_cannot_access_moderator_panel(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/moderator');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_moderator_can_access_moderator_panel(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator']);

        $response = $this->actingAs($moderator)->get('/moderator');
        $response->assertStatus(200);
    }

    public function test_client_can_access_client_panel(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/client');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_users_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
    }

    public function test_client_cannot_access_users_management(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_products_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/products');
        $response->assertStatus(200);
    }

    public function test_moderator_can_access_products_management(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator']);

        $response = $this->actingAs($moderator)->get('/products');
        $response->assertStatus(200);
    }

    public function test_client_cannot_access_products_management(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/products');
        $response->assertStatus(403);
    }
}
