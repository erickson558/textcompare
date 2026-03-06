<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/Application/Version.php';
require_once dirname(__DIR__) . '/src/Infrastructure/JsonResponse.php';

use TextCompare\Application\Version;
use TextCompare\Infrastructure\JsonResponse;

JsonResponse::send([
    'version' => Version::value(),
    'service' => 'TextCompare',
]);
