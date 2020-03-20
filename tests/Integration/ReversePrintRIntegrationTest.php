<?php
declare(strict_types=1);

namespace Tests\Integration\ReversePrintR;

use Tests\Integration\ReversePrintR\Fixtures\ClassWithAllTypes;
use PHPUnit\Framework\TestCase;
use ReversePrintR\Exception\MalformedPrintROutputException;
use ReversePrintR\ReversePrintR;

class ReversePrintRIntegrationTest extends TestCase
{
    public function testNullIsNull(): void
    {
        $reverser = new ReversePrintR(print_r(null, true));
        $this->assertNull($reverser->reverse());
    }

    public function testIntegerIsInteger(): void
    {
        $reverser = new ReversePrintR(print_r(1, true));
        $this->assertSame(1, $reverser->reverse());
    }

    public function testIntegerAsStringIsInteger(): void
    {
        $reverser = new ReversePrintR(print_r('1', true));
        $this->assertSame(1, $reverser->reverse());
    }

    public function testFloatIsFloat(): void
    {
        $reverser = new ReversePrintR(print_r(2.3, true));
        $this->assertSame(2.3, $reverser->reverse());
    }

    public function testFloatAsStringIsFloat(): void
    {
        $reverser = new ReversePrintR(print_r('4.5', true));
        $this->assertSame(4.5, $reverser->reverse());
    }

    public function testStringIsString(): void
    {
        $reverser = new ReversePrintR(print_r('simple string', true));
        $this->assertSame('simple string', $reverser->reverse());
    }

    public function testEmptyStringIsNull(): void
    {
        $reverser = new ReversePrintR(print_r('', true));
        $this->assertNull($reverser->reverse());
    }

    public function testEmptyArray(): void
    {
        $array = [];
        $reverser = new ReversePrintR(print_r($array, true));
        $this->assertSame($array, $reverser->reverse());
    }

    public function testArrayWithoutKeys(): void
    {
        $array = [
            'string',
            1,
            2.3,
            null
        ];
        $reverser = new ReversePrintR(print_r($array, true));
        $this->assertSame($array, $reverser->reverse());
    }

    public function testArrayWithAllValueTypes(): void
    {
        $array = [
            'firstInt' => 1,
            'firstString' => 'string',
            'firstFloat' => 2.3,
            'firstSubArray' => [
                'subKey' => 'subValue',
            ],
        ];
        $reverser = new ReversePrintR(print_r($array, true));
        $this->assertSame($array, $reverser->reverse());
    }

    public function testOneLineArrayThrowsException(): void
    {
        $reverser = new ReversePrintR('Array');

        $this->expectException(MalformedPrintROutputException::class);

        $reverser->reverse();
    }

    public function testObjectWithAllValueTypes(): void
    {
        $class = new ClassWithAllTypes();
        $reverser = new ReversePrintR(print_r($class, true));

        $this->assertEquals($class, $reverser->reverse());
    }
}
