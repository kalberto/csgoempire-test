<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Http\Requests\DeletePlayerRequest;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Repositories\PlayerRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlayerController extends Controller
{

    private PlayerRepository $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        return PlayerResource::collection($this->playerRepository->getAllPlayers());
    }

    public function show(int $playerId): PlayerResource
    {
        return new PlayerResource($this->playerRepository->getPlayerById($playerId));
    }

    public function store(StorePlayerRequest $request): PlayerResource
    {
        return new PlayerResource($this->playerRepository->createPlayer($request->validated()));
    }

    public function update(UpdatePlayerRequest $request, int $playerId): PlayerResource
    {
        return new PlayerResource($this->playerRepository->updatePlayer($playerId, $request->validated()));
    }

    public function destroy(DeletePlayerRequest $request, int $playerId)
    {
        if ($this->playerRepository->deletePlayer($playerId) ) {
            return response()->json([], 204);
        }
        return response()->json([
            'Couldn\'t delete id: ' . $playerId
        ], 500);
    }
}
