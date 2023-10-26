<?php

namespace App\Interfaces;

interface PlayerRepositoryInterface
{
    public function getAllPlayers();
    public function getPlayerById(int $playerId);
    public function createPlayer(array $data);
    public function updatePlayer(int $playerId, array $data);
    public function deletePlayer(int $playerId);
}
