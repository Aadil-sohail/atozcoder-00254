<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_user_management(): void
    {
        $response = $this->get(route('users.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_user_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_authenticated_user_can_create_a_new_user(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new-user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'new-user@example.com']);
    }

    public function test_authenticated_user_can_update_another_user(): void
    {
        $admin = User::factory()->create();
        $user = User::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($admin)->put(route('users.update', $user), [
            'name' => 'New Name',
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
    }

    public function test_authenticated_user_can_delete_another_user(): void
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_cannot_delete_their_own_account_from_user_management(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_public_registration_is_disabled(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }
}
