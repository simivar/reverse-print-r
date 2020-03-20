<?php
declare(strict_types=1);

namespace Tests\Unit\ReversePrintR\Handler;

use PHPUnit\Framework\TestCase;
use ReversePrintR\Exception\MalformedPrintROutputException;
use ReversePrintR\Handler\ArrayHandler;
use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\HandlerRunnerInterface;

class ArrayHandlerUnitTest extends TestCase
{
    public function testThrowsExceptionWhenMissingOpeningArrayToken(): void
    {
        $this->expectException(MalformedPrintROutputException::class);
        $this->expectExceptionMessageMatches(
            '/Expected opening token "Array", got "(.*)" on line [0-9]{1,}\./'
        );

        $mock = $this->createMock(LinesCollectionInterface::class);
        $mock->method('getNextLine')->willReturnOnConsecutiveCalls(
            '(',
            '[0] => value',
            ')'
        );

        $handler = new ArrayHandler();
        $handler->handle($mock);
    }

    public function testThrowsExceptionWhenArrayMissingOpeningBracket(): void
    {
        $this->expectException(MalformedPrintROutputException::class);
        $this->expectExceptionMessageMatches(
            '/Expected opening bracket after compound type keyword on line [0-9]{1,}\./'
        );

        $mock = $this->createMock(LinesCollectionInterface::class);
        $mock->method('getNextLine')->willReturnOnConsecutiveCalls(
            'Array',
            '[0] => value',
            ')'
        );

        $handler = new ArrayHandler();
        $handler->handle($mock);
    }

    public function testThrowsExceptionWhenMalformedArrayValue(): void
    {
        $mock = $this->createMock(LinesCollectionInterface::class);
        $return = [
            'Array',
            '(',
            'test => value',
            ')',
        ];
        $mock->method('getLines')->willReturn($return);
        $mock->method('getNextLine')->willReturnOnConsecutiveCalls(
            'Array',
            '(',
            'test => value',
            ')'
        );

        $this->expectException(MalformedPrintROutputException::class);
        $this->expectExceptionMessageMatches(
            '/Expected array\'s "\[key] => value" line, not (.*)\./'
        );

        $handler = new ArrayHandler();
        $handler->handle($mock);
    }

    public function testThrowsExceptionWhenArrayMissingClosingBracket(): void
    {
        $this->expectException(MalformedPrintROutputException::class);
        $this->expectExceptionMessageMatches(
            '/Could NOT find closing bracket of "Array" on line [0-9]{1,}\./'
        );

        $mock = $this->createMock(LinesCollectionInterface::class);
        $mock->method('getNextLine')->willReturnOnConsecutiveCalls(
            'Array',
            '(',
            '[0] => value'
        );

        $handlerRunnerMock = $this->createMock(HandlerRunnerInterface::class);
        $handlerRunnerMock->method('run')->willReturn('value');

        $handler = new ArrayHandler();
        $handler->setHandlerRunner($handlerRunnerMock);
        $handler->handle($mock);
    }

    /**
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideNullValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideStringValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideIntegerValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideFloatValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideObjectValues
     */
    public function testValidateReturnsFalseOnNotArrayLikeValue(string $value): void
    {
        $handler = new ArrayHandler();
        $this->assertFalse($handler->shouldBeUsed($value));
    }

    /**
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideArrayValues
     */
    public function testValidateReturnsTrueOnArrayLikeValues(string $value): void
    {
        $handler = new ArrayHandler();
        $this->assertTrue($handler->shouldBeUsed($value));
    }
}