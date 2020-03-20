<?php
declare(strict_types=1);

namespace ReversePrintR\Handler;

use ReversePrintR\Collection\LinesCollectionInterface;

final class NullHandler implements HandlerInterface
{
    public function handle(LinesCollectionInterface $linesCollection)
    {
        $linesCollection->getNextLine();

        return null;
    }

    public function shouldBeUsed(string $line): bool
    {
        return $line === '';
    }
}
