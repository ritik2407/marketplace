<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceSanctumAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_via_sanctum_api(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'API Test User',
            'email' => 'apitest@example.com',
            'password' => 'password123',
            'phone' => '+91 99999 88888',
            'city' => 'Mumbai',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'token_type',
                'access_token',
                'user' => ['id', 'name', 'email', 'phone', 'city'],
            ])
            ->assertJson([
                'status' => 'success',
                'user' => [
                    'email' => 'apitest@example.com',
                    'name' => 'API Test User',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'apitest@example.com',
        ]);
    }

    public function test_user_can_login_via_sanctum_api_and_get_token(): void
    {
        $user = User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'loginuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'token_type',
                'access_token',
                'user' => ['id', 'name', 'email'],
            ])
            ->assertJson([
                'status' => 'success',
            ]);

        $token = $response->json('access_token');
        $this->assertNotEmpty($token);

        // Test authenticated profile route
        $profileResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user');

        $profileResponse->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'user' => [
                    'email' => 'loginuser@example.com',
                ],
            ]);
    }

    public function test_user_can_logout_and_revoke_sanctum_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Sanctum token revoked. Logged out successfully.',
            ]);

        $this->assertCount(0, $user->fresh()->tokens);
    }
}
