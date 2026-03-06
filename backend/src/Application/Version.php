<?php

declare(strict_types=1);

namespace TextCompare\Application;

final class Version
{
    public static function value(): string
    {
        $path = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'VERSION';

        if (!is_file($path)) {
            return 'V0.0.0';
        }

        $value = trim((string) file_get_contents($path));

        return $value !== '' ? $value : 'V0.0.0';
    }
}
