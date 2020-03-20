<?php
declare(strict_types=1);

namespace ReversePrintR\Handler;

use ReversePrintR\Collection\LinesCollectionInterface;
use ReversePrintR\Exception\ClassNotFoundException;
use ReflectionClass;
use ReflectionProperty;
use ReversePrintR\Exception\MalformedPrintROutputException;
use ReversePrintR\Service\StringEndsWithService;
use function preg_match;
use function sprintf;

final class ObjectHandler extends CompoundHandlerAbstract implements HandlerInterface
{
    public function handle(LinesCollectionInterface $linesCollection)
    {
        $className = $this->validateOpeningToken($linesCollection);
        $this->validateOpeningBracket($linesCollection);
        $reflectionClass = $this->createClassReflection($className);

        $resultObject = $reflectionClass->newInstanceWithoutConstructor();
        $foundClosingBracket = false;
        while ($lineData = $linesCollection->getNextLine()) {
            if ($lineData === ')') {
                $this->validateClosingBracket($linesCollection);

                $foundClosingBracket = true;
                break;
            }

            $parsedLine = $this->parseLine($lineData);
            $linesCollection->addNextLine($parsedLine['value']);
            $value = $this->getHandlerRunner()->run($linesCollection);

            if ($className === 'stdClass') {
                $resultObject->{$parsedLine['property']} = $value;
            } else {
                $property = $reflectionClass->getProperty($parsedLine['property']);
                switch ($parsedLine['visibility']) {
                    case ReflectionProperty::IS_PRIVATE:
                    case ReflectionProperty::IS_PROTECTED:
                        $property->setAccessible(true);
                        $property->setValue($resultObject, $value);
                        $property->setAccessible(false);
                        break;
                    case ReflectionProperty::IS_PUBLIC:
                        $property->setValue($resultObject, $value);
                }
            }
        }

        if (!$foundClosingBracket) {
            throw new MalformedPrintROutputException(sprintf(
                'Could NOT find closing bracket of "Object" on line %d.',
                $linesCollection->getCurrentLineNumber()
            ));
        }

        return $resultObject;
    }

    private function parseLine(string $line)
    {
        $pregResult = preg_match('#^\[(.*)] =>(.*)$#', $line, $matches);
        if ($pregResult === 1) {
            $visibility = ReflectionProperty::IS_PUBLIC;
            if (StringEndsWithService::endsWith($matches[1], ':private')) {
                $visibility = ReflectionProperty::IS_PRIVATE;
            }

            if (StringEndsWithService::endsWith($matches[1], ':protected')) {
                $visibility = ReflectionProperty::IS_PROTECTED;
            }

            return [
                'property' => explode(':', $matches[1])[0],
                'visibility' => $visibility,
                'value' => $matches[2],
            ];
        }

        throw new MalformedPrintROutputException(sprintf(
            'Expected object\'s "[key] => value" line, not "%s".',
            $line
        ));
    }

    private function createClassReflection(string $className): ReflectionClass
    {
        if (!class_exists($className)) {
            throw new ClassNotFoundException(sprintf(
                'Could NOT find class "%s".',
                $className
            ));
        }

        return new ReflectionClass($className);
    }

    public function shouldBeUsed(string $line): bool
    {
        return preg_match('/^\S+? Object$/', $line) === 1;
    }

    protected function validateOpeningToken(LinesCollectionInterface $linesCollection): string
    {
        $line = $linesCollection->getNextLine();
        $result = preg_match('/^(\S+?) Object$/', $line, $matches);
        if ($result !== 1) {
            throw new MalformedPrintROutputException(sprintf(
                'Opening token of Object has to be in "ClassName Object" format, got "%s" on line %d.',
                $line,
                $linesCollection->getCurrentLineNumber()
            ));
        }

        return $matches[1];
    }
}
