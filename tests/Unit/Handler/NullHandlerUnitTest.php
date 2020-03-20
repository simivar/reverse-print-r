<?php
declare(strict_types=1);

namespace Tests\Unit\ReversePrintR\Handler;

use PHPUnit\Framework\TestCase;
use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\Handler\NullHandler;

class NullHandlerUnitTest extends TestCase
{
    public function testHandlerReturnsNull(): void
    {
        $mock = $this->createMock(LinesCollectionInterface::class);
        $mock->method('getNextLine')->willReturnOnConsecutiveCalls('');
        $mock->method('lookupNextLine')->willReturnOnConsecutiveCalls('');

        $handler = new NullHandler();
        $this->assertNull($handler->handle($mock));
    }

    /**
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideFloatValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideStringValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideIntegerValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideArrayValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideObjectValues
     */
    public function testValidateReturnsFalseOnNotNullLikeValue(string $value): void
    {
        $handler = new NullHandler();
        $this->assertFalse($handler->shouldBeUsed($value));
    }

    /**
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideNullValues
     */
    public function testValidateReturnsTrueOnNullLikeValue(string $value): void
    {
        $handler = new NullHandler();
        $this->assertTrue($handler->shouldBeUsed($value));
    }
}