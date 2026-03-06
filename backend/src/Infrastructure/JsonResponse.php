<?php

namespace TextCompare\Infrastructure;

final class JsonResponse
{
    /**
     * @param array<string, mixed> $payload
     */
    public static function send($payload, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');

        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
