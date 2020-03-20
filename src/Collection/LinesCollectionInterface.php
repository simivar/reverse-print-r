<?php
declare(strict_types=1);

namespace ReversePrintR\Collection;

interface LinesCollectionInterface
{
    public function __construct(array $lines);

    public function addNextLine(string $line): void;

    public function getNextLine(): ?string;

    public function lookupNextLine(): ?string;

    public function hasNextLine(): bool;

    public function getCurrentLineNumber(): int;

    public function getLines(): array;

    public function isOneLineOutput(): bool;
}
