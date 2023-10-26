<?php


// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class PlayerControllerCreateTest extends PlayerControllerBaseTest
{
    public function test_can_create_player()
    {
        $data = [
            "name" => "test",
            "position" => "defender",
            "playerSkills" => [
                0 => [
                    "skill" => "attack",
                    "value" => 60
                ],
                1 => [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ];

        $res = $this->postJson(self::REQ_URI, $data);


        $this->assertEquals([
                'id' => 1,
                'name' => 'test',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 1,
                        'skill' => 'attack',
                        'value' => 60,
                        'playerId' => 1,
                    ],
                    [
                        'id' => 2,
                        'skill' => 'speed',
                        'value' => 80,
                        'playerId' => 1,
                    ],
                ],
            ], $res->json());
    }

    public function test_create_player_invalid_position()
    {
        $data = [
            "name" => "test",
            "position" => "no_exist",
            "playerSkills" => [
                0 => [
                    "skill" => "attack",
                    "value" => 60
                ],
                1 => [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ];

        $res = $this->postJson(self::REQ_URI, $data);

        $this->assertEquals([
            'message' => 'Invalid value for position: no_exist'
        ], $res->json());
    }

    public function test_create_player_invalid_skill()
    {
        $data = [
            "name" => "test",
            "position" => "midfielder",
            "playerSkills" => [
                0 => [
                    "skill" => "no_exist",
                    "value" => 60
                ],
                1 => [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ];

        $res = $this->postJson(self::REQ_URI, $data);

        $this->assertEquals([
            'message' => 'Invalid value for playerSkills.0.skill: no_exist'
        ], $res->json());
    }

    public function test_create_player_zero_skill()
    {
        $data = [
            "name" => "test",
            "position" => "midfielder",
            "playerSkills" => [],
        ];

        $res = $this->postJson(self::REQ_URI, $data);

        $this->assertEquals([
            'message' => 'The player skills field is required.'
        ], $res->json());
    }
}
