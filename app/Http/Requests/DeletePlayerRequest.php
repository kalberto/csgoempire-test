<?php

namespace App\Http\Requests;

class DeletePlayerRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|exists:players',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['id' => $this->route('id')]);
    }
}
