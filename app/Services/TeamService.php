<?php

namespace App\Services;

use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use App\Models\Player;
use App\Repositories\PlayerRepository;
use Illuminate\Support\Collection;

class TeamService
{

    private PlayerRepository $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function process(array $requirements): Collection
    {
        $playersId =  $this->getPlayersByRequirements($requirements);

        return Player::query()->whereIn('id', $playersId)->get();
    }

    public function getPlayersByRequirements(array $requirements, array $usedPlayersIds = [], bool $bySkill = true): array
    {
        $missingRequirements = [];
        $playerIds = [];

        foreach ($requirements as $requirement) {
            $position = PlayerPosition::from($requirement['position']);
            $mainSkill = PlayerSkill::from($requirement['mainSkill']);
            $numberOfPlayers = $requirement['numberOfPlayers'];

            if ($bySkill) {
                $players = $this->playerRepository->getPlayersIdsByPositionAndSkill(
                    $position,
                    $mainSkill,
                    $numberOfPlayers,
                    $usedPlayersIds
                );

                if ($numberOfPlayers > $players->count()) {
                    $requirement['numberOfPlayers'] -= $players->count();
                    $missingRequirements[] = $requirement;
                }
            } else {
                $players = $this->playerRepository->getPlayersIdsByPositionExcludingSkill(
                    $position,
                    $mainSkill,
                    $numberOfPlayers,
                    $usedPlayersIds
                );
            }

            if ($players->isNotEmpty()) {
                $playerIds[] = $players->pluck('id')->toArray();
            }
        }

        $playerIds = array_merge(...$playerIds);

        if ($missingRequirements) {
            $playerIds = array_merge(
                $playerIds,
                $this->getPlayersByRequirements($missingRequirements, $playerIds, false)
            );
        }

        return $playerIds;
    }
}
