<?php
declare(strict_types=1);

namespace Tests\Unit\ReversePrintR\Service;

use PHPUnit\Framework\TestCase;
use ReversePrintR\Service\StringToLinesService;

class StringToLinesServiceUnitTest extends TestCase
{
    public function testReturnsOneElementArrayOnOneLineString(): void
    {
        $this->assertEquals([''], StringToLinesService::split(''));
        $this->assertEquals([''], StringToLinesService::split(' '));
        $this->assertEquals(['0'], StringToLinesService::split('0'));
    }

    public function testReturnsMultiValueArrayOnMultiLineString(): void
    {
        $this->assertEquals(['line 1', 'line 2'], StringToLinesService::split('line 1' . PHP_EOL . 'line 2'));
    }
}