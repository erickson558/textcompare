<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/Domain/TextCompareService.php';
require_once dirname(__DIR__) . '/src/Infrastructure/JsonResponse.php';

use TextCompare\Domain\TextCompareService;
use TextCompare\Infrastructure\JsonResponse;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    JsonResponse::send(['error' => 'Method not allowed. Use POST.'], 405);
    return;
}

$rawBody = file_get_contents('php://input');
$payload = json_decode($rawBody ?: '', true);

if (!is_array($payload)) {
    JsonResponse::send(['error' => 'Invalid JSON payload.'], 400);
    return;
}

$left = (string) ($payload['leftText'] ?? '');
$right = (string) ($payload['rightText'] ?? '');

$maxCharacters = 200000;
if (strlen($left) > $maxCharacters || strlen($right) > $maxCharacters) {
    JsonResponse::send(['error' => 'Input exceeds 200000 characters limit.'], 413);
    return;
}

$service = new TextCompareService();
$result = $service->compare($left, $right);

JsonResponse::send(['data' => $result]);
