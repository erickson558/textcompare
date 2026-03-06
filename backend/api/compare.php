<?php

require_once dirname(__DIR__) . '/src/Domain/TextCompareService.php';
require_once dirname(__DIR__) . '/src/Infrastructure/JsonResponse.php';

use TextCompare\Domain\TextCompareService;
use TextCompare\Infrastructure\JsonResponse;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    JsonResponse::send(array('error' => 'Metodo no permitido. Usa POST.'), 405);
    return;
}

$rawBody = file_get_contents('php://input');
$payload = json_decode($rawBody ?: '', true);

if (!is_array($payload)) {
    JsonResponse::send(array('error' => 'JSON invalido.'), 400);
    return;
}

$left = isset($payload['leftText']) ? (string) $payload['leftText'] : '';
$right = isset($payload['rightText']) ? (string) $payload['rightText'] : '';

$maxCharacters = 200000;
if (strlen($left) > $maxCharacters || strlen($right) > $maxCharacters) {
    JsonResponse::send(array('error' => 'La entrada excede el limite de 200000 caracteres.'), 413);
    return;
}

$service = new TextCompareService();
$result = $service->compare($left, $right);

JsonResponse::send(array('data' => $result));
