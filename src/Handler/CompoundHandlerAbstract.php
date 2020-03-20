<?php
declare(strict_types=1);

namespace ReversePrintR\Handler;

use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\Exception\MalformedPrintROutputException;
use ReversePrintR\HandlerRunnerInterface;

abstract class CompoundHandlerAbstract implements CompoundHandlerInterface
{
    /** @var HandlerRunnerInterface */
    protected $handlerRunner;

    public function setHandlerRunner(HandlerRunnerInterface $handlerRunner): void
    {
        if ($this->handlerRunner === null) {
            $this->handlerRunner = $handlerRunner;
        }
    }

    public function getHandlerRunner(): HandlerRunnerInterface
    {
        return $this->handlerRunner;
    }

    abstract protected function validateOpeningToken(LinesCollectionInterface $linesCollection): string;

    protected function validateOpeningBracket(LinesCollectionInterface $linesCollection): void
    {
        if ($linesCollection->getNextLine() !== '(') {
            throw new MalformedPrintROutputException(sprintf(
                'Expected opening bracket after compound type keyword on line %d.',
                $linesCollection->getCurrentLineNumber()
            ));
        }
    }

    protected function validateClosingBracket(LinesCollectionInterface $linesCollection): void
    {
        $nextLine = $linesCollection->getNextLine();
        if ($nextLine !== '' && $linesCollection->hasNextLine()) {
            throw new MalformedPrintROutputException(sprintf(
                'Closing bracket of compound type MUST be followed by empty line on line %d.',
                $linesCollection->getCurrentLineNumber()
            ));
        }
    }
}
