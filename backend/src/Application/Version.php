<?php

namespace TextCompare\Application;

final class Version
{
    public static function value()
    {
        $path = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'VERSION';

        if (!is_file($path)) {
            return 'V0.0.0';
        }

        $value = trim((string) file_get_contents($path));

        return $value !== '' ? $value : 'V0.0.0';
    }
}
