<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class PlayerControllerListingTest extends PlayerControllerBaseTest
{
    public function test_sample(): void
    {
        $this->createSinglePlayer();

        $res = $this->get(self::REQ_URI);

        $this->assertEquals([
            [
                'id' => 1,
                'name' => 'player 1',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 1,
                        'skill' => 'attack',
                        'value' => 43,
                        'playerId' => 1,
                    ],
                    [
                        'id' => 2,
                        'skill' => 'speed',
                        'value' => 40,
                        'playerId' => 1,
                    ],
                ],
            ],
        ], $res->json());

        $res->assertStatus(200);

        $this->assertNotNull($res);
    }
}
