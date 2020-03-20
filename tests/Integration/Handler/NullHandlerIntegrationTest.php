<?php
declare(strict_types=1);

namespace Tests\Unit\ReversePrintR\Handler;

use PHPUnit\Framework\TestCase;
use ReversePrintR\Collection\LinesCollection;
use ReversePrintR\Handler\NullHandler;

class NullHandlerIntegrationTest extends TestCase
{
    public function testHandlerReturnsNull(): void
    {
        $collection = new LinesCollection(['']);

        $handler = new NullHandler();
        $this->assertNull($handler->handle($collection));
    }
}