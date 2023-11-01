<?php

namespace App\Services;

use App\Models\Player;
use App\Repositories\PlayerRepository;
use App\Repositories\PlayerSkillRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PlayerService
{
    protected PlayerRepository $playerRepository;

    protected PlayerSkillRepository $playerSkillRepository;

    public function __construct(PlayerRepository $playerRepository, PlayerSkillRepository $playerSkillRepository)
    {
        $this->playerRepository = $playerRepository;
        $this->playerSkillRepository = $playerSkillRepository;
    }

    public function getAllPlayers(): Collection
    {
        return $this->playerRepository->getAllPlayers();
    }

    public function getPlayerById(int $playerId): ?Player
    {
        return $this->playerRepository->getPlayerById($playerId);
    }

    public function createPlayer(mixed $validated): Player
    {
        $player = $this->playerRepository->createPlayer($validated);

        $this->playerSkillRepository->createSkills($player->id, $validated['playerSkills']);

        return $player;
    }

    public function updatePlayer(int $playerId, mixed $validated): ?Player
    {
        $player = $this->getPlayerById($playerId);

        if ($player) {

            return DB::transaction(function () use ($player, $validated, $playerId) {
                $player->update($validated);
                $this->playerSkillRepository->updateSkills($playerId, $validated['playerSkills']);

                return $player->refresh();
            }, 3);
        }

        return null;
    }

    public function deletePlayer(int $playerId): bool
    {
        return DB::transaction(function() use ($playerId) {
            $this->playerSkillRepository->deleteSkillsByPlayerId($playerId);

            return $this->playerRepository->deletePlayer($playerId);
        }, 3);
    }
}
