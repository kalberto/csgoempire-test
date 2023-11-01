<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Services\PlayerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlayerController extends Controller
{
    private PlayerService $playerService;

    /**
     * PlayerController constructor.
     *
     * @param PlayerService $playerService
     *
     */
    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * Get all players.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return PlayerResource::collection($this->playerService->getAllPlayers());
    }

    /**
     * Get a single player.
     *
     * @param int $playerId
     * @return PlayerResource|JsonResponse
     */
    public function show(int $playerId): PlayerResource|JsonResponse
    {
        $player = $this->playerService->getPlayerById($playerId);

        if ($player) {
            return new PlayerResource($player);
        }

        return response()->json([
            'message' => 'Player not found: '.$playerId,
        ], 404);
    }

    /**
     * Create a player.
     *
     * @param StorePlayerRequest $request
     * @return PlayerResource
     */
    public function store(StorePlayerRequest $request): PlayerResource
    {
        return new PlayerResource($this->playerService->createPlayer($request->validated()));
    }

    /**
     * Update a player.
     *
     * @param UpdatePlayerRequest $request
     * @param int $playerId
     * @return PlayerResource|JsonResponse
     */
    public function update(UpdatePlayerRequest $request, int $playerId): PlayerResource|JsonResponse
    {
        $player = $this->playerService->updatePlayer($playerId, $request->validated());

        if ($player) {
            return new PlayerResource($player);
        }

        return response()->json([
            'message' => 'Player not found: '.$playerId,
        ], 404);
    }

    /**
     * Delete a player.
     *
     * @param int $playerId
     * @return JsonResponse
     */
    public function destroy(int $playerId): JsonResponse
    {
        if ($this->playerService->deletePlayer($playerId)) {
            return response()->json([
                'message' => 'Player deleted',
            ]);
        }

        return response()->json([
            'message' => 'Player not found: '.$playerId,
        ], 404);
    }
}
