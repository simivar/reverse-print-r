<?php
declare(strict_types=1);

namespace Tests\Unit\ReversePrintR\Handler;

use PHPUnit\Framework\TestCase;
use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\Exception\ClassNotFoundException;
use ReversePrintR\Exception\MalformedPrintROutputException;
use ReversePrintR\Handler\ObjectHandler;

class ObjectHandlerUnitTest extends TestCase
{
    public function testThrowsExceptionWhenWrongOpeningTag(): void
    {
        $this->expectException(MalformedPrintROutputException::class);
        $this->expectExceptionMessage(
            'Opening token of Object has to be in "ClassName Object" format, got "Wrong Class Name Object" on line 0.'
        );

        $mock = $this->createMock(LinesCollectionInterface::class);
        $return = [
            'Wrong Class Name Object',
            '(',
            ')',
        ];
        $mock->method('getLines')->willReturn($return);
        $mock->method('getNextLine')->willReturnOnConsecutiveCalls(
            'Wrong Class Name Object',
            '(',
            ')'
        );

        $handler = new ObjectHandler();
        $handler->handle($mock);
    }

    public function testThrowsExceptionOnNotLoadedClass(): void
    {
        $this->expectException(ClassNotFoundException::class);
        $this->expectExceptionMessage('Could NOT find class "Some\Random\Namespace\ClassName".');

        $mock = $this->createMock(LinesCollectionInterface::class);
        $return = [
            'Some\\Random\\Namespace\\ClassName Object',
            '(',
            ')',
        ];
        $mock->method('getLines')->willReturn($return);
        $mock->method('getNextLine')->willReturnOnConsecutiveCalls(
            'Some\\Random\\Namespace\\ClassName Object',
            '(',
            ')'
        );

        $handler = new ObjectHandler();
        $handler->handle($mock);
    }

    public function testThrowsExceptionWhenMissingOpeningBracket(): void
    {
        $this->expectException(MalformedPrintROutputException::class);
        $this->expectExceptionMessageMatches(
            '/Expected opening bracket after compound type keyword on line [0-9]{1,}\./'
        );

        $mock = $this->createMock(LinesCollectionInterface::class);
        $mock->method('getNextLine')->willReturnOnConsecutiveCalls(
            'stdClass Object',
            '[0] => value',
            ')'
        );

        $handler = new ObjectHandler();
        $handler->handle($mock);
    }

    /**
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideNullValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideStringValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideFloatValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideArrayValues
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideIntegerValues
     */
    public function testValidateReturnsFalseOnNotObjectLikeValue(string $value): void
    {
        $handler = new ObjectHandler();
        $this->assertFalse($handler->shouldBeUsed($value));
    }

    /**
     * @dataProvider \Tests\Unit\ReversePrintR\DataProviders\HandlersDataProvider::provideObjectValues
     */
    public function testValidateReturnsTrueOnObjectLikeValue(string $value): void
    {
        $handler = new ObjectHandler();
        $this->assertTrue($handler->shouldBeUsed($value));
    }
}