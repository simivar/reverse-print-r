<?php
declare(strict_types=1);

namespace Tests\Unit\ReversePrintR\Collection;

use PHPUnit\Framework\TestCase;
use ReversePrintR\Collection\LinesCollection;
use ReversePrintR\Exception\MalformedPrintROutputException;

class LinesCollectionUnitTest extends TestCase
{
    public function testConstructorThrowsExceptionOnEmptyLines(): void
    {
        $this->expectException(MalformedPrintROutputException::class);
        $this->expectExceptionMessage('Collection must contain at least one line.');

        new LinesCollection([]);
    }

    public function testAddNextLineAddsLineAtEnd(): void
    {
        $collection = new LinesCollection(['first line']);
        $collection->addNextLine('second line');

        $this->assertSame('second line', $collection->getNextLine());
        $this->assertSame('first line', $collection->getNextLine());
    }

    public function testGetNextLineReturnsLine(): void
    {
        $collection = new LinesCollection(['first line', 'second line']);

        $this->assertSame('first line', $collection->getNextLine());
        $this->assertSame('second line', $collection->getNextLine());
    }

    public function testGetNextLineRemovesLine(): void
    {
        $collection = new LinesCollection(['first line', 'second line']);

        $collection->getNextLine();
        $this->assertSame([0 => 'second line'], $collection->getLines());
    }

    public function testGetNextLineTrimsLines(): void
    {
        $array = [' first line '];
        $collection = new LinesCollection($array);

        $this->assertSame('first line', $collection->getNextLine());
    }

    public function testLookupNextLineDoesntRemoveLine(): void
    {
        $collection = new LinesCollection(['first line', 'second line']);

        $collection->lookupNextLine();
        $this->assertSame(['second line', 'first line'], $collection->getLines());
    }

    public function testLookupNextLineTrimsLines(): void
    {
        $array = [' first line '];
        $collection = new LinesCollection($array);

        $this->assertSame('first line', $collection->lookupNextLine());
    }

    public function testGetNextLineTrimsLine(): void
    {
        $collection = new LinesCollection([' first line ']);
        $this->assertSame('first line', $collection->getNextLine());
    }

    public function testGetNextLineIncreasesCounter(): void
    {
        $collection = new LinesCollection(['first line', 'second line']);

        $collection->getNextLine();
        $this->assertSame(2, $collection->getCurrentLineNumber());
    }

    public function testIsOneLineReturnsTrueOnSingleValueArray(): void
    {
        $collection = new LinesCollection(['']);
        $this->assertTrue($collection->isOneLineOutput());
    }

    public function testIsOneLineReturnsFalseOnMultilineValueArray(): void
    {
        $collection = new LinesCollection(['first line', 'second line']);
        $this->assertFalse($collection->isOneLineOutput());
    }
}