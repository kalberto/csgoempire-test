<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamProcessRequest;
use App\Http\Resources\PlayerResource;
use App\Services\TeamService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TeamController extends Controller
{
    private TeamService $teamService;

    /**
     * TeamController constructor.
     *
     * @param TeamService $teamService
     *
     */
    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Get the best team based on positions and skills requirements.
     *
     * @param TeamProcessRequest $request
     * @return AnonymousResourceCollection
     */
    public function process(TeamProcessRequest $request): AnonymousResourceCollection
    {
        $requirements = array_filter($request->all(), static function ($value) {
            return is_array($value) && isset($value['position'], $value['mainSkill'], $value['numberOfPlayers']);
        });

        return PlayerResource::collection($this->teamService->process($requirements));
    }
}
