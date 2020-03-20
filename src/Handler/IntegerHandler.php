<?php
declare(strict_types=1);

namespace ReversePrintR\Handler;

use ReversePrintR\Collection\LinesCollectionInterface;

final class IntegerHandler implements HandlerInterface
{
    public function handle(LinesCollectionInterface $linesCollection): int
    {
        return (int) $linesCollection->getNextLine();
    }

    public function shouldBeUsed(string $line): bool
    {
        $asInt = (int)$line;
        return ((string)$asInt) === $line;
    }
}
