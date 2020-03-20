<?php
declare(strict_types=1);

namespace ReversePrintR\Handler;

use ReversePrintR\Collection\LinesCollectionInterface;

interface HandlerInterface
{
    public function handle(LinesCollectionInterface $linesCollection);

    public function shouldBeUsed(string $line): bool;
}
