<?php
declare(strict_types=1);

namespace ReversePrintR\Service;

final class StringEndsWithService
{
    public static function endsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        if ($length === 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
}