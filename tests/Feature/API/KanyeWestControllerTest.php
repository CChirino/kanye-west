<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class KanyeWestControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetRandomQuotes()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->actingAs($user)
                         ->withToken($token)
                         ->get('api/v1/quote/show');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                                '*' => [
                                    'quote',
                                ],
                             ],
                    ]);
    }

    public function testGetNumberRandomQuotes()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->actingAs($user)
                         ->withToken($token)
                         ->get('api/v1/quote/number-quotes?number=3');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                                 '*' => [
                                     'quote',
                                 ],
                             ],
                    ])
                ->assertJsonCount(3, 'data');
    }
}
