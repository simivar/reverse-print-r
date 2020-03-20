<?php
declare(strict_types=1);

namespace ReversePrintR\Handler;

use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\Exception\MalformedPrintROutputException;
use function sprintf;
use function preg_match;

final class ArrayHandler extends CompoundHandlerAbstract implements HandlerInterface
{
    public function handle(LinesCollectionInterface $linesCollection): array
    {
        $this->validateOpeningToken($linesCollection);
        $this->validateOpeningBracket($linesCollection);

        $resultArray = [];
        $foundClosingBracket = false;
        while ($lineData = $linesCollection->getNextLine()) {
            if ($lineData === ')') {
                $this->validateClosingBracket($linesCollection);

                $foundClosingBracket = true;
                break;
            }

            $parsedLine = $this->parseLine($lineData);
            $linesCollection->addNextLine($parsedLine['value']);
            $resultArray[$parsedLine['key']] = $this->getHandlerRunner()->run($linesCollection);
        }

        if (!$foundClosingBracket) {
            throw new MalformedPrintROutputException(sprintf(
                'Could NOT find closing bracket of "Array" on line %d.',
                $linesCollection->getCurrentLineNumber()
            ));
        }

        return $resultArray;
    }

    private function parseLine(string $value): array
    {
        $pregResult = preg_match('#^\[(.*)] =>(.*)$#', $value, $matches);
        if ($pregResult === 1) {
            return [
                'key' => $matches[1],
                'value' => $matches[2],
            ];
        }

        throw new MalformedPrintROutputException(sprintf(
            'Expected array\'s "[key] => value" line, not "%s".',
            $value
        ));
    }

    public function shouldBeUsed(string $line): bool
    {
        return $line === 'Array';
    }

    protected function validateOpeningToken(LinesCollectionInterface $linesCollection): string
    {
        $line = $linesCollection->getNextLine();
        if ($line !== 'Array') {
            throw new MalformedPrintROutputException(sprintf(
                'Expected opening token "Array", got "%s" on line %d.',
                $line,
                $linesCollection->getCurrentLineNumber()
            ));
        }

        return 'Array';
    }
}
