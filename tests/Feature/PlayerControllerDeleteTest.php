<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;

class PlayerControllerDeleteTest extends PlayerControllerBaseTest
{

    public function test_unauthenticated(): void
    {
        $res = $this->delete(self::REQ_URI.'1', headers: [
            'Accept' => 'application/json',
        ]);

        $res->assertUnauthorized();
    }

    public function test_invalid_id(): void
    {
        $res = $this->delete(self::REQ_URI.'1', headers: [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.config('auth.api_token'),
        ]);

        $this->assertEquals([
            'message' => 'Player not found: 1',
        ], $res->json());
    }

    public function test_delete_user(): void
    {
        $this->createSinglePlayer();
        $this->createSinglePlayer();

        $this->assertDatabaseCount('players', 2);
        $this->assertDatabaseCount('player_skills', 4);

        $res = $this->delete(self::REQ_URI.'1', headers: [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.config('auth.api_token'),
        ]);

        $this->assertDatabaseCount('player_skills', 2);

        $res->assertStatus(200);
    }
}
