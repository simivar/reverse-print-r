<?php
declare(strict_types=1);

namespace ReversePrintR\Service;

final class StringToLinesService
{
    public static function split($string): array
    {
        return preg_split('#\r?\n#', trim($string));
    }
}