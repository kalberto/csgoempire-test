<?php

namespace App\Repositories;

use App\Enums\PlayerPosition;
use App\Interfaces\PlayerRepositoryInterface;
use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Database\Eloquent\Collection;

class PlayerRepository implements PlayerRepositoryInterface
{

    public function getAllPlayers()
    {
        return Player::all();
    }

    public function getPlayerById(int $playerId): Player
    {
        return Player::find($playerId);
    }

    public function createPlayer(array $data): Player
    {
        $player = Player::create($data);

        $this->createPlayerSkills($player->id, $data['playerSkills']);

        return $player;
    }

    public function updatePlayer(int $playerId, array $data): Player
    {
        $player = $this->getPlayerById($playerId);
        $player->update($data);

        foreach ($player->skills as $key => $skill) {
            if (isset($data['playerSkills'][$key])) {
                $skill->update($data['playerSkills'][$key]);
                unset($data['playerSkills'][$key]);
            } else {
                $skill->delete();
            }
        }

        $this->createPlayerSkills($player->id, $data['playerSkills']);

        return $player->refresh();
    }

    public function deletePlayer(int $playerId): bool
    {
        $player = $this->getPlayerById($playerId);
        if (isset($player)) {
            $skillsIds = $player->skills->pluck('id')->toArray();
            PlayerSkill::query()->whereIn('id', $skillsIds)->delete();
            return $player->delete();
        }

        return false;
    }

    public function getPlayersIdsByPositionAndSkill(PlayerPosition $position, \App\Enums\PlayerSkill $skill, int $limit, array $usedPlayersIds): Collection
    {
        return Player::query()
            ->select('players.id')
            ->join('player_skills', 'players.id', '=', 'player_skills.player_id')
            ->where('position', $position)
            ->where('player_skills.skill', $skill)
            ->whereNotIn('players.id', $usedPlayersIds)
            ->orderByDesc('player_skills.value')
            ->groupBy('players.id')
            ->limit($limit)
            ->get();
    }

    public function getPlayersIdsByPositionExcludingSkill(PlayerPosition $position, \App\Enums\PlayerSkill $skill, int $limit, array $usedPlayersIds): Collection
    {
        return Player::query()
            ->select('players.id')
            ->join('player_skills', 'players.id', '=', 'player_skills.player_id')
            ->where('position', $position)
            ->whereNotIn('players.id', $usedPlayersIds)
            ->whereNot('player_skills.skill', $skill)
            ->orderByDesc('player_skills.value')
            ->groupBy('players.id')
            ->limit($limit)
            ->get();
    }

    protected function createPlayerSkills(int $playerId, array $playerSkills): void
    {
        foreach ($playerSkills as $playerSkill) {
            PlayerSkill::create(array_merge($playerSkill, ['player_id' => $playerId]));
        }
    }
}
