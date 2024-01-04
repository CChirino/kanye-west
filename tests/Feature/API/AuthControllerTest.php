<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanRegister()
    {
        $userData = [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'lastname' => 'Doe',
            'admin' => false

        ];

        $response = $this->postJson('/api/v1/register', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User register successfully.',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }

    public function testUserCanLogin()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'admin' => false

        ]);

        $loginData = [
            'email' => 'john@example.com',
            'password' => 'password',
            'admin' => false

        ];

        $response = $this->postJson('/api/v1/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'name',
                ],
            ]);
    }

    public function testUserCanLogout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->actingAs($user)
            ->postJson('/api/v1/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Successfully logged out',
            ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'token' => hash('sha256', $token),
        ]);
    }

}