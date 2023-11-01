<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\PlayerSkillRepositoryInterface;
use App\Models\PlayerSkill;

class PlayerSkillRepository implements PlayerSkillRepositoryInterface
{
    public function getAllByPlayerId(int $playerId): Collection|array
    {
        return PlayerSkill::query()->where('player_id', $playerId)->get();
    }

    public function createSkills(int $playerId, array $playerSkills): void
    {
        foreach ($playerSkills as $playerSkill) {
            PlayerSkill::create(array_merge($playerSkill, ['player_id' => $playerId]));
        }
    }

    public function updateSkills(int $playerId, array $playerSkills): void
    {
        $skills = $this->getAllByPlayerId($playerId);

        foreach ($skills as $key => $skill) {
            if (isset($playerSkills[$key])) {
                $skill->update($playerSkills[$key]);
                unset($playerSkills[$key]);
            } else {
                $skill->delete();
            }
        }

        $this->createSkills($playerId, $playerSkills);
    }

    public function deleteSkillsByPlayerId(int $playerId): int
    {
        return PlayerSkill::query()->where('player_id', $playerId)->delete();
    }
}
