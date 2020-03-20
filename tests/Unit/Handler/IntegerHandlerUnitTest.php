<?php
declare(strict_types=1);

namespace Tests\Unit\ReversePrintR\Handler;

use PHPUnit\Framework\TestCase;
use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\Handler\IntegerHandler;

class IntegerHandlerUnitTest extends TestCase
{
    public function testHandlerReturnsFloat(): void
    {
        $mock = $this->createMock(LinesCollectionInterface::class);
        $mock->method('getNextLine')->willReturnOnConsecutiveCalls('3', '3');

        $handler = new IntegerHandler();
        $this->assertSame(3, $handler->handle($mock));
    }

    /**
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideNullValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideStringValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideFloatValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideArrayValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideObjectValues
     */
    public function testValidateReturnsFalseOnNotIntegerLikeValue(string $value): void
    {
        $handler = new IntegerHandler();
        $this->assertFalse($handler->shouldBeUsed($value));
    }

    /**
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideIntegerValues
     */
    public function testValidateReturnsTrueOnIntegerLikeValue(string $value): void
    {
        $handler = new IntegerHandler();
        $this->assertTrue($handler->shouldBeUsed($value));
    }
}