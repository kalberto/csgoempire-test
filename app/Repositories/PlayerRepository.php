<?php

namespace App\Repositories;

use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use App\Interfaces\PlayerRepositoryInterface;
use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

class PlayerRepository implements PlayerRepositoryInterface
{
    public function getAllPlayers(): Collection
    {
        return Player::all();
    }

    public function getPlayerById(int $playerId): ?Player
    {
        return Player::find($playerId);
    }

    public function createPlayer(array $data): Player
    {
        return Player::create($data);
    }

    public function deletePlayer(int $playerId): bool
    {
        $player = $this->getPlayerById($playerId);

        if (isset($player)) {
            return $player->delete();
        }

        return false;
    }

    /**
     * Get a lists of players ids that match the desired position and skill
     *
     * @param PlayerPosition $position
     * @param PlayerSkill $skill
     * @param int $limit
     * @param array $usedPlayersIds
     * @return Collection
     */
    public function getPlayersIdsByPositionAndSkill(PlayerPosition $position, PlayerSkill $skill, int $limit, array $usedPlayersIds): Collection
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

    /**
     * Get a lists of players ids that match the desired position and don't have the specific skill
     *
     * @param PlayerPosition $position
     * @param PlayerSkill $skill
     * @param int $limit
     * @param array $usedPlayersIds
     * @return Collection
     */
    public function getPlayersIdsByPositionExcludingSkill(PlayerPosition $position, PlayerSkill $skill, int $limit, array $usedPlayersIds): Collection
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
}
