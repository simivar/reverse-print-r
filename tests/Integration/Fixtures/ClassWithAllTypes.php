<?php
declare(strict_types=1);

namespace Tests\Integration\ReversePrintR\Fixtures;

class ClassWithAllTypes
{
    public $string = 'text';

    protected $integer = 1;

    private $float = 2.3;

    private $array = [
        'i' => 4,
        'f' => 5.6,
        's' => 'in array',
        'n' => null,
    ];

    private $null = null;

    private $class;

    public function __construct()
    {
        $this->class = new SimpleClass();
    }
}
