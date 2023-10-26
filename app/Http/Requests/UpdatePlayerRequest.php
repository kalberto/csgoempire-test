<?php

namespace App\Http\Requests;

use App\Enums\PlayerPosition;
use App\Enums\PlayerSkill;
use Illuminate\Validation\Rules\Enum;

class UpdatePlayerRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|exists:players',
            'name' => 'required|string',
            'position' => ['required', new Enum(PlayerPosition::class)],
            'playerSkills' => ['required', 'array', 'min:1'],
            'playerSkills.*.skill' => ['required', 'distinct:strict', new Enum(PlayerSkill::class)],
            'playerSkills.*.value' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'Invalid value for :attribute: :input',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['id' => $this->route('id')]);
    }
}
