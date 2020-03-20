<?php
declare(strict_types=1);

namespace Tests\Unit\ReversePrintR\DataProviders;

class HandlersDataProvider
{
    public function provideArrayValues(): array
    {
        return [
            'array string' => ['Array'],
        ];
    }

    public function provideObjectValues(): array
    {
        return [
            'object string' => ['stdClass Object'],
        ];
    }

    public static function provideIntegerValues(): array
    {
        return [
            'integer as string' => ['1'],
        ];
    }

    public static function provideNullValues(): array
    {
        return [
            'empty string' => [''],
        ];
    }

    public static function provideStringValues(): array
    {
        return [
            'string' => ['test string'],
            'string beginning with int' => ['1 and only'],
            'string beginning with float' => ['1.52 and only'],
            'string ending with int' => ['we are the 1'],
            'string with int inside' => ['only 1 int'],
        ];
    }

    public static function provideFloatValues(): array
    {
        return [
            'float as string' => ['1.23'],
        ];
    }
}