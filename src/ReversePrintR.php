<?php
declare(strict_types=1);

namespace ReversePrintR;

use ReversePrintR\Handler\ArrayHandler;
use ReversePrintR\Handler\FloatHandler;
use ReversePrintR\Handler\IntegerHandler;
use ReversePrintR\Handler\NullHandler;
use ReversePrintR\Handler\ObjectHandler;
use ReversePrintR\Service\StringToLinesService;
use ReversePrintR\Collection\LinesCollection;

final class ReversePrintR
{
    /** @var LinesCollection */
    private $linesCollection;

    /** @var ?HandlerRunner */
    private $handlerRunner;

    public function __construct(string $printROutput, ?HandlerRunnerInterface $handlerRunner = null)
    {
        $this->linesCollection = new LinesCollection(StringToLinesService::split($printROutput));
        $this->handlerRunner = $handlerRunner;
    }

    public function reverse()
    {
        return $this->getHandlerRunner()->run($this->linesCollection);
    }

    private function getHandlerRunner(): HandlerRunnerInterface
    {
        if ($this->handlerRunner === null) {
            $this->handlerRunner = new HandlerRunner(
                new FloatHandler(),
                new IntegerHandler(),
                new NullHandler(),
                new ArrayHandler(),
                new ObjectHandler()
            );
        }

        return $this->handlerRunner;
    }
}
