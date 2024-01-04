<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\FavoriteQuote;

class FavoriteQuotesControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->actingAs($user)
                         ->withToken($token)
                         ->get('api/v1/favorite/index');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data',
                 ]);
    }
    public function testStore()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $quote = 'This is a favorite quote.';

        $response = $this->actingAs($user)
                         ->withToken($token)
                         ->post('api/v1/favorite/store', [
                             'quote' => $quote,
                         ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data',
                 ]);
    }

    public function testDelete()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $favoriteQuote = FavoriteQuote::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
                         ->withToken($token)
                         ->delete('api/v1/favorite/delete/'.$favoriteQuote->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Favorite Quote deleted successfully.',
                 ]);
    }
}
