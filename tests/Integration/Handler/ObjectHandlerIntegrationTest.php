<?php
declare(strict_types=1);

namespace Tests\Integration\ReversePrintR\Handler;

use PHPUnit\Framework\TestCase;
use ReversePrintR\Exception\MalformedPrintROutputException;
use ReversePrintR\Handler\ArrayHandler;
use ReversePrintR\Collection\LinesCollection;
use ReversePrintR\Handler\FloatHandler;
use ReversePrintR\Handler\IntegerHandler;
use ReversePrintR\Handler\NullHandler;
use ReversePrintR\Handler\ObjectHandler;
use ReversePrintR\HandlerRunner;

class ObjectHandlerIntegrationTest extends TestCase
{
    /** @var HandlerRunner */
    private $handler;

    public function setUp(): void
    {
        $this->handler = new HandlerRunner(
            new IntegerHandler(),
            new FloatHandler(),
            new NullHandler(),
            new ArrayHandler(),
            new ObjectHandler()
        );
    }

    public function testReturnsProperValuesWithSubarray(): void
    {
        $arrayAsString = [
            'Array',
            '(',
            '    [subArray] => Array',
            '        (',
            '            [0] => value',
            '            [1] => 1',
            '            [2] => 2.3',
            '            [3] => ',
            '        )',
            '',
            ')',
        ];
        $handler = new ArrayHandler();
        $handler->setHandlerRunner($this->handler);
        $values = $handler->handle(new LinesCollection($arrayAsString));
        $this->assertSame([
            'subArray' => [
                'value',
                1,
                2.3,
                null,
            ],
        ], $values);
    }

    public function testReturnsProperStringValue(): void
    {
        $handler = new ArrayHandler();
        $handler->setHandlerRunner($this->handler);
        $values = $handler->handle(new LinesCollection([
            'Array',
            '(',
            '    [0] => value',
            ')',
        ]));

        $this->assertSame(['value'], $values);
    }

    public function testReturnsProperIntegerValue(): void
    {
        $handler = new ArrayHandler();
        $handler->setHandlerRunner($this->handler);
        $values = $handler->handle(new LinesCollection([
            'Array',
            '(',
            '    [0] => 1',
            ')',
        ]));

        $this->assertSame([1], $values);
    }

    public function testReturnsProperFloatValue(): void
    {
        $handler = new ArrayHandler();
        $handler->setHandlerRunner($this->handler);
        $values = $handler->handle(new LinesCollection([
            'Array',
            '(',
            '    [0] => 2.3',
            ')',
        ]));

        $this->assertSame([2.3], $values);
    }

    public function testReturnsProperNullValue(): void
    {
        $handler = new ArrayHandler();
        $handler->setHandlerRunner($this->handler);
        $values = $handler->handle(new LinesCollection([
            'Array',
            '(',
            '    [0] => ',
            ')',
        ]));

        $this->assertSame([null], $values);
    }

    public function testThrowsExceptionWhenMissingNewlineAfterSubarrayClosingBracket(): void
    {
        $this->expectException(MalformedPrintROutputException::class);
        $this->expectExceptionMessageMatches(
            '/Could NOT find closing bracket of "Array" on line [0-9]{1,}\./'
        );

        $arrayAsString = [
            'Array',
            '(',
            '    [subArray] => Array',
            '        (',
            '            [0] => value',
            '            [1] => 1',
            '            [2] => 2.3',
            '            [3] => ',
            '        )',
            ')',
        ];
        $handler = new ArrayHandler();
        $handler->setHandlerRunner($this->handler);
        $handler->handle(new LinesCollection($arrayAsString));
    }
}