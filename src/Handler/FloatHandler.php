<?php
declare(strict_types=1);

namespace ReversePrintR\Handler;

use ReversePrintR\Collection\LinesCollectionInterface;

final class FloatHandler implements HandlerInterface
{
    public function handle(LinesCollectionInterface $linesCollection): float
    {
        return (float) $linesCollection->getNextLine();
    }

    public function shouldBeUsed(string $line): bool
    {
        $integerHandler = new IntegerHandler();

        $asFloat = (float)$line;
        return ((string)$asFloat) === $line && !$integerHandler->shouldBeUsed($line);
    }
}
