<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use App\Repositories\PlayerRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class PlayerControllerBaseTest extends TestCase
{
    use RefreshDatabase;

    final const REQ_URI = '/api/player/';
    final const REQ_TEAM_URI = '/api/team/process';


    protected function createSinglePlayer(): void
    {
        $playerRespository = new PlayerRepository();

        $playerRespository->createPlayer([
            "name" => "player 1",
            "position" => "defender",
            "playerSkills" => [
                [
                    "skill" => "attack",
                    "value" => 43
                ],
                [
                    "skill" => "speed",
                    "value" => 40
                ]
            ]
        ]);
    }

    protected function log($data){
        fwrite(STDERR, print_r($data, TRUE));
    }
}
