<?php

namespace App\Rules;

use App\Models\Player;
use Illuminate\Contracts\Validation\Rule;

class EnoughPlayer implements Rule
{

    public function passes($attribute, $value): bool
    {
        $playerCount = Player::query()->where('position', $attribute)
            ->count();

        return $playerCount >= $value;
    }

    public function message(): string
    {
        return 'Insufficient number of players for position :attribute: :input';
    }
}
