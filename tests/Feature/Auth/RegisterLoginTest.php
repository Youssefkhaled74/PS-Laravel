<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RegisterLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user()
    {
        $payload = [
            'full_name' => 'Test User',
            'country_code' => '+966',
            'phone' => '5' . mt_rand(10000000, 99999999),
            'email' => null,
            'password' => 'password',
            'password_confirmation' => 'password',
            'accept_terms' => true,
        ];

        $resp = $this->postJson('/api/auth/register', $payload);

        $resp->assertStatus(201)->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', ['phone' => $payload['phone']]);
    }

    public function test_login_returns_token()
    {
        /** @var \App\Models\User $user */
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['phone_verified_at' => now()]);

        $resp = $this->postJson('/api/auth/login', [
            'country_code' => $user->country_code,
            'phone' => $user->phone,
            'password' => 'password',
        ]);

        $resp->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token', 'token_type', 'user'],
                'errors',
                'meta',
            ]);
    }

    public function test_logout_revokes_token()
    {
        /** @var \App\Models\User $user */
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['phone_verified_at' => now()]);

        $this->actingAs($user, 'sanctum');

        $resp = $this->postJson('/api/auth/logout');

        $resp->assertStatus(200)->assertJson(['success' => true]);
    }
}
