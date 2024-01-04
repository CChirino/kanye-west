<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShow()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        
        $response = $this->actingAs($user)
                         ->withToken($token)
                         ->get('api/v1/profile/show');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User data.',
                     'data' => $user->toArray()
                 ]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'John',
            'email' => 'john.doe@example.com',
            'lastname' => 'Doe'
        ];
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->post('api/v1/profile/update', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User data updated successfully.',
                     'data' => [
                         'name' => 'John',
                         'email' => 'john.doe@example.com',
                         'lastname' => 'Doe'
                     ]
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'John',
            'email' => 'john.doe@example.com',
            'lastname' => 'Doe'
        ]);
    }
}