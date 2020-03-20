<?php

namespace ReversePrintR;

use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\Handler\CompoundHandlerInterface;
use ReversePrintR\Handler\HandlerInterface;

final class HandlerRunner implements HandlerRunnerInterface
{
    /** @var HandlerInterface[] */
    private $handlers;

    public function __construct(?HandlerInterface ...$handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @inheritDoc
     */
    public function run(LinesCollectionInterface $linesCollection)
    {
        $returnValue = '';
        $usedHandler = false;

        $nextLine = $linesCollection->lookupNextLine();
        foreach ($this->getHandlers() as $handler) {
            if (!$handler->shouldBeUsed($nextLine)) {
                continue;
            }

            if ($handler instanceof CompoundHandlerInterface) {
                $handler->setHandlerRunner($this);
            }

            $returnValue = $handler->handle($linesCollection);
            $usedHandler = true;
        }

        if (!$usedHandler) {
            $linesCollection->getNextLine();
            return $nextLine;
        }

        return $returnValue;
    }

    /**
     * @inheritDoc
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }
}
