<?php
declare(strict_types=1);

namespace Tests\Unit\ReversePrintR\Handler;

use PHPUnit\Framework\TestCase;
use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\Handler\FloatHandler;

class FloatHandlerUnitTest extends TestCase
{
    public function testHandlerReturnsFloat(): void
    {
        $mock = $this->createMock(LinesCollectionInterface::class);
        $mock->method('getNextLine')->willReturnOnConsecutiveCalls('1.2', '1.2');

        $handler = new FloatHandler();
        $this->assertSame(1.2, $handler->handle($mock));
    }

    /**
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideNullValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideStringValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideIntegerValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideArrayValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideObjectValues
     */
    public function testValidateReturnsFalseOnNotFloatLikeValue(string $value): void
    {
        $handler = new FloatHandler();
        $this->assertFalse($handler->shouldBeUsed($value));
    }

    /**
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideFloatValues
     */
    public function testValidateReturnsTrueOnFloatLikeValue(string $value): void
    {
        $handler = new FloatHandler();
        $this->assertTrue($handler->shouldBeUsed($value));
    }
}