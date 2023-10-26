<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamProcessRequest;
use App\Http\Resources\PlayerResource;
use App\Services\TeamService;

class TeamController extends Controller
{

    private TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function process(TeamProcessRequest $request)
    {
        $requirements = array_filter($request->all(), static function ($value) {
           return is_array($value) && isset($value['position'], $value['mainSkill'], $value['numberOfPlayers']);
        });



        return PlayerResource::collection($this->teamService->process($requirements));
    }
}
