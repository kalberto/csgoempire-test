<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomValidationException extends ValidationException
{
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], 422);
    }
}
