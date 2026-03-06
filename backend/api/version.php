<?php

require_once dirname(__DIR__) . '/src/Application/Version.php';
require_once dirname(__DIR__) . '/src/Infrastructure/JsonResponse.php';

use TextCompare\Application\Version;
use TextCompare\Infrastructure\JsonResponse;

JsonResponse::send(array(
    'version' => Version::value(),
    'service' => 'TextCompare',
));
