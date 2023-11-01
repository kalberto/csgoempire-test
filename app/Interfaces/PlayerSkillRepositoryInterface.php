<?php

namespace App\Interfaces;

interface PlayerSkillRepositoryInterface
{
    public function getAllByPlayerId(int $playerId);

    public function createSkills(int $playerId, array $playerSkills);

    public function updateSkills(int $playerId, array $playerSkills);

    public function deleteSkillsByPlayerId(int $playerId);
}
