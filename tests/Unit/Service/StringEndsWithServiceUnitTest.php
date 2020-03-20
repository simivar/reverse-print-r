<?php
declare(strict_types=1);

namespace ReversePrintR\Service;

use PHPUnit\Framework\TestCase;

class StringEndsWithServiceUnitTest extends TestCase
{
    public function testReturnsTrueForEmptyNeedle(): void
    {
        $this->assertTrue(StringEndsWithService::endsWith('string', ''));
    }

    public function testReturnsTrueOnSameEnding(): void
    {
        $this->assertTrue(StringEndsWithService::endsWith('property:visibility', ':visibility'));
    }

    public function testReturnsFalseOnDifferentEnding(): void
    {
        $this->assertFalse(StringEndsWithService::endsWith('aa', 'b'));
    }

    public function testDoesntIgnoreWhitespaces(): void
    {
        $this->assertFalse(StringEndsWithService::endsWith('1', ' 1'));
    }
}
