<?php
declare(strict_types=1);

namespace ReversePrintR\Handler;

use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\HandlerRunnerInterface;

interface CompoundHandlerInterface
{
    public function setHandlerRunner(HandlerRunnerInterface $handlerRunner): void;

    public function getHandlerRunner(): HandlerRunnerInterface;
}
