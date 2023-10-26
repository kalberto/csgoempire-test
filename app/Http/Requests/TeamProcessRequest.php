<?php

namespace App\Http\Requests;

use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use App\Helpers\TeamHelper;
use App\Rules\EnoughPlayer;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Enum;

class TeamProcessRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            PlayerPosition::DEFENDER->value => [new EnoughPlayer()],
            PlayerPosition::MIDFIELDER->value => [new EnoughPlayer()],
            PlayerPosition::FORWARD->value => [new EnoughPlayer()],
            '*.position' => [new Enum(PlayerPosition::class)],
            '*.mainSkill' => [new Enum(PlayerSkill::class)],
            '*.unique' => ['distinct:strict'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $requirements = TeamHelper::getValidPlayerRequirements($this->all());

        $requirements = array_map(function ($item) {
            return array_merge($item, [
                'unique' => $item['position'] . '_' . $item['mainSkill'],
            ]);
        }, $requirements);

        $numberOfPlayersByPosition = collect($requirements)->groupBy('position')
            ->mapWithKeys(function (Collection $items,  string $position) {
                return [$position => $items->sum('numberOfPlayers')];
            })->toArray();

        $this->replace(array_merge($requirements, $numberOfPlayersByPosition));
    }

    public function messages(): array
    {
        return [
            '*.unique.distinct' => 'Duplicated requirement: :input',
        ];
    }
}
