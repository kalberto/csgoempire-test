<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use App\Repositories\PlayerRepository;
use App\Repositories\PlayerSkillRepository;
use App\Services\PlayerService;

class TeamControllerTest extends PlayerControllerBaseTest
{
    protected bool $playersCreated = false;

    public function test_can_return_player(): void
    {
        $this->createPlayers();
        $requirements =
            [
                [
                    'position' => 'defender',
                    'mainSkill' => 'speed',
                    'numberOfPlayers' => 1,
                ],
            ];

        $res = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $res->assertStatus(200);

        $this->assertEquals([
            [
                'id' => 1,
                'name' => 'player defender 1',
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
                        'value' => 60,
                        'playerId' => 1,
                    ],
                ],
            ],
        ], $res->json());
    }

    public function test_return_highest_main_skill(): void
    {
        $this->createPlayers();
        $requirements =
            [
                [
                    'position' => 'defender',
                    'mainSkill' => 'stamina',
                    'numberOfPlayers' => 1,
                ],
            ];

        $res = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $res->assertStatus(200);

        $this->assertEquals([
            [
                'id' => 4,
                'name' => 'player defender 4',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 7,
                        'skill' => 'stamina',
                        'value' => 90,
                        'playerId' => 4,
                    ],
                ],
            ],
        ], $res->json());
    }

    public function test_skill_fallback(): void
    {
        $this->createPlayers();

        $requirements = [
            [
                'position' => 'defender',
                'mainSkill' => 'strength',
                'numberOfPlayers' => 3,
            ],
        ];

        $res = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $res->assertStatus(200);

        $this->assertEquals([
            [
                'id' => 2,
                'name' => 'player defender 2',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 3,
                        'skill' => 'strength',
                        'value' => 10,
                        'playerId' => 2,
                    ],
                    [
                        'id' => 4,
                        'skill' => 'stamina',
                        'value' => 15,
                        'playerId' => 2,
                    ],
                ],
            ],
            [
                'id' => 3,
                'name' => 'player defender 3',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 5,
                        'skill' => 'strength',
                        'value' => 100,
                        'playerId' => 3,
                    ],
                    [
                        'id' => 6,
                        'skill' => 'stamina',
                        'value' => 15,
                        'playerId' => 3,
                    ],
                ],
            ],
            [
                'id' => 4,
                'name' => 'player defender 4',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 7,
                        'skill' => 'stamina',
                        'value' => 90,
                        'playerId' => 4,
                    ],
                ],
            ],
        ], $res->json());
    }

    public function test_skill_fallback_highest_skill(): void
    {
        $this->createPlayers();

        $requirements = [
            [
                'position' => 'defender',
                'mainSkill' => 'attack',
                'numberOfPlayers' => 2,
            ],
        ];

        $res = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $res->assertStatus(200);

        $this->assertEquals([
            [
                'id' => 1,
                'name' => 'player defender 1',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 1,
                        'skill' => 'attack',
                        'value' => 60,
                        'playerId' => 1,
                    ],
                    1 => [
                        'id' => 2,
                        'skill' => 'speed',
                        'value' => 60,
                        'playerId' => 1,
                    ],
                ],
            ],
            [
                'id' => 3,
                'name' => 'player defender 3',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 5,
                        'skill' => 'strength',
                        'value' => 100,
                        'playerId' => 3,
                    ],
                    [
                        'id' => 6,
                        'skill' => 'stamina',
                        'value' => 15,
                        'playerId' => 3,
                    ],
                ],
            ],
        ], $res->json());
    }

    public function test_skill_best_match(): void
    {
        // This test if to test the ability to match the best team,
        // meaning that we first must search for every main skill and then for the fallback

        $this->createPlayers();

        $requirements = [
            [
                'position' => 'defender',
                'mainSkill' => 'attack',
                'numberOfPlayers' => 2,
            ],
            [
                'position' => 'defender',
                'mainSkill' => 'strength',
                'numberOfPlayers' => 1,
            ],
        ];

        $res = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $res->assertStatus(200);

        $this->assertEquals([
            [
                'id' => 1,
                'name' => 'player defender 1',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 1,
                        'skill' => 'attack',
                        'value' => 60,
                        'playerId' => 1,
                    ],
                    1 => [
                        'id' => 2,
                        'skill' => 'speed',
                        'value' => 60,
                        'playerId' => 1,
                    ],
                ],
            ],
            [
                'id' => 3,
                'name' => 'player defender 3',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 5,
                        'skill' => 'strength',
                        'value' => 100,
                        'playerId' => 3,
                    ],
                    [
                        'id' => 6,
                        'skill' => 'stamina',
                        'value' => 15,
                        'playerId' => 3,
                    ],
                ],
            ],
            [
                'id' => 4,
                'name' => 'player defender 4',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'id' => 7,
                        'skill' => 'stamina',
                        'value' => 90,
                        'playerId' => 4,
                    ],
                ],
            ],

        ], $res->json());
    }

    public function test_insufficient_players(): void
    {
        $this->createPlayers();

        $requirements = [
            [
                'position' => 'forward',
                'mainSkill' => 'speed',
                'numberOfPlayers' => 2,
            ],
        ];

        $res = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $res->assertStatus(422);

        $this->assertEquals([
            'message' => 'Insufficient number of players for position: forward',
        ], $res->json());
    }

    public function test_can_return_same_requirement(): void
    {
        $this->createPlayers();

        $requirements = [
            [
                'position' => 'defender',
                'mainSkill' => 'attack',
                'numberOfPlayers' => 1,
            ],
            [
                'position' => 'defender',
                'mainSkill' => 'attack',
                'numberOfPlayers' => 1,
            ],
        ];

        $res = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $res->assertStatus(422);

        $this->assertEquals([
            'message' => 'Duplicated requirement: defender_attack',
        ], $res->json());
    }

    protected function createPlayers(): void
    {
        if ($this->playersCreated) {
            return;
        }
        $this->playersCreated = true;

        $playerService = new PlayerService(new PlayerRepository(), new PlayerSkillRepository());

        $players = [
            [
                'name' => 'player defender 1',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'skill' => 'attack',
                        'value' => 60,
                    ],
                    [
                        'skill' => 'speed',
                        'value' => 60,
                    ],
                ],
            ],
            [
                'name' => 'player defender 2',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'skill' => 'strength',
                        'value' => 10,
                    ],
                    [
                        'skill' => 'stamina',
                        'value' => 15,
                    ],
                ],
            ],
            [
                'name' => 'player defender 3',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'skill' => 'strength',
                        'value' => 100,
                    ],
                    [
                        'skill' => 'stamina',
                        'value' => 15,
                    ],
                ],
            ],
            [
                'name' => 'player defender 4',
                'position' => 'defender',
                'playerSkills' => [
                    [
                        'skill' => 'stamina',
                        'value' => 90,
                    ],
                ],
            ],
            [
                'name' => 'player midfielder 1',
                'position' => 'midfielder',
                'playerSkills' => [
                    [
                        'skill' => 'attack',
                        'value' => 10,
                    ],
                    [
                        'skill' => 'speed',
                        'value' => 20,
                    ],
                ],
            ],
            [
                'name' => 'player midfielder 2',
                'position' => 'midfielder',
                'playerSkills' => [
                    [
                        'skill' => 'attack',
                        'value' => 60,
                    ],
                    [
                        'skill' => 'speed',
                        'value' => 60,
                    ],
                ],
            ],
        ];

        foreach ($players as $player) {
            $playerService->createPlayer($player);
        }
    }
}
