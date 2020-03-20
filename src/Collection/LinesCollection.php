<?php

namespace ReversePrintR\Collection;

use ReversePrintR\Exception\MalformedPrintROutputException;
use function array_reverse;
use function count;
use function trim;

/**
 * Because of performance Collection reverses the order of $lines array and goes from end of array to beginning.
 * @see \Tests\Benchmark\CollectionGettingAndAddingLines
 */
final class LinesCollection implements LinesCollectionInterface
{
    /** @var array */
    private $lines;

    /** @var int */
    private $currentLine;

    public function __construct(array $lines)
    {
        if (empty($lines)) {
            throw new MalformedPrintROutputException('Collection must contain at least one line.');
        }

        $this->lines = array_reverse($lines);
        $this->currentLine = 1;
    }

    public function addNextLine(string $line): void
    {
        $this->lines[] = $line;
    }

    public function getNextLine(): ?string
    {
        if (!$this->hasNextLine()) {
            return null;
        }

        $this->currentLine++;
        return trim(array_pop($this->lines));
    }

    public function lookupNextLine(): ?string
    {
        if (!$this->hasNextLine()) {
            return null;
        }

        return trim($this->lines[count($this->lines) - 1]);
    }

    public function hasNextLine(): bool
    {
        return count($this->lines) !== 0;
    }

    public function getCurrentLineNumber(): int
    {
        return $this->currentLine;
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function isOneLineOutput(): bool
    {
        return count($this->lines) === 1;
    }
}