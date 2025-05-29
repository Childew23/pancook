<?php

namespace App\Tests\Unit;

use Cocur\Slugify\Slugify;
use PHPUnit\Framework\TestCase;

class SlugifyTest extends TestCase
{

    public function testAccentsAreRemoved(): void
    {
        self::assertSame('creme-brulee', (new Slugify())->slugify('Crème brûlée'));
    }

        public function testWhitespaceAndCase(): void
    {
        self::assertSame('hello-world', (new Slugify())->slugify('  Hello   WORLD  '));
    }
}
