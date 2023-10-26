<?php

namespace App\Helpers;

class TeamHelper
{
    public static function getValidPlayerRequirements(array $params): array
    {
        return array_filter($params, static function ($value) {
            return is_array($value) && isset($value['position'], $value['mainSkill'], $value['numberOfPlayers']);
        });
    }
}
