<?php

namespace Tests\Benchmark;

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\OutputTimeUnit;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;
use function array_fill;
use function array_shift;
use function array_unshift;
use function array_reverse;
use function array_pop;

/**
 * @Revs(4000)
 * @Iterations(10)
 * @Warmup(2)
 * @BeforeMethods({"generateArray"})
 * @OutputTimeUnit("seconds", precision=6)
 */
class CollectionGettingAndAddingLines
{
    private const ARRAY_LENGTH = 1000;

    private $array;

    public function generateArray(): void
    {
        $this->array = array_fill(0, self::ARRAY_LENGTH, 'line');
        $this->array[0] = 'first line';
        $this->array[999] = 'last line';
    }

    public function benchArrayShift(): void
    {
        for ($i = 0; $i < self::ARRAY_LENGTH; $i++) {
            array_shift($this->array);
            array_unshift($this->array, 'new first line');
        }
    }

    public function benchReverseAndPop(): void
    {
        array_reverse($this->array);
        for ($i = 0; $i < self::ARRAY_LENGTH; $i++) {
            array_pop($this->array);
            $this->array[] = 'new first line';
        }
    }
}
