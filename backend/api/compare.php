<?php

require_once dirname(__DIR__) . '/src/Domain/TextCompareService.php';
require_once dirname(__DIR__) . '/src/Infrastructure/JsonResponse.php';

use TextCompare\Domain\TextCompareService;
use TextCompare\Infrastructure\JsonResponse;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    JsonResponse::send(array('error' => 'Method not allowed. Use POST.'), 405);
    return;
}

$rawBody = file_get_contents('php://input');
$payload = json_decode($rawBody ?: '', true);

if (!is_array($payload)) {
    JsonResponse::send(array('error' => 'Invalid JSON payload.'), 400);
    return;
}

$left = isset($payload['leftText']) ? (string) $payload['leftText'] : '';
$right = isset($payload['rightText']) ? (string) $payload['rightText'] : '';

$maxCharacters = 200000;
if (strlen($left) > $maxCharacters || strlen($right) > $maxCharacters) {
    JsonResponse::send(array('error' => 'Input exceeds 200000 characters limit.'), 413);
    return;
}

$service = new TextCompareService();
$result = $service->compare($left, $right);

JsonResponse::send(array('data' => $result));
