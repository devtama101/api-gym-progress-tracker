<?php

namespace App\Utils;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Response
{
    public static function success($payload = [], $status_code = 200)
    {
        $payload = (object) $payload;

        return response()->json([
            'message' => $payload->message ?? 'Success',
            'data' => $payload->data ?? null,
            'error' => $payload->error ?? null,
        ], $payload->status_code ?? $status_code);
    }

    public static function failed($payload = [], $status_code = 400)
    {
        $payload = (object) $payload;

        Log::error([
            'message' => $payload->message ?? 'Failed response sent',
            'error' => $payload->error ?? null,
            'user_id' => Auth::id() ?? 'guest',
        ]);

        return response()->json([
            'message' => $payload->message ?? 'Failed',
            'data' => $payload->data ?? null,
            'error' => $payload->error ?? null,
        ], $payload->status_code ?? $status_code);
    }
}
