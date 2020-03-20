<?php
declare(strict_types=1);

namespace ReversePrintR;

use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\Handler\HandlerInterface;

interface HandlerRunnerInterface
{
    public function __construct(?HandlerInterface ...$handlers);

    /**
     * @param LinesCollectionInterface $linesCollection
     * @return int|float|array|null|string|object
     */
    public function run(LinesCollectionInterface $linesCollection);

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers(): array;
}
