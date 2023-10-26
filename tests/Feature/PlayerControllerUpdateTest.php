<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

class PlayerControllerUpdateTest extends PlayerControllerBaseTest
{
    public function test_can_update()
    {
        $this->createSinglePlayer();

        $created = $this->get(self::REQ_URI. '1');

        $this->assertEquals([
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
        ], $created->json());

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

        $res = $this->putJson(self::REQ_URI . '1', $data);

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

    public function test_invalid_id()
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

        $res = $this->putJson(self::REQ_URI . '56', $data);

        $this->assertEquals([
            'message' => 'Invalid value for id: 56',
        ], $res->json());
    }
}
