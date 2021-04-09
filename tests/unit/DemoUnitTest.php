<?php

declare(strict_types=1);

namespace Zept\unit;

class DemoUnitTest extends BaseUnitTest
{
    public function testHelloWorld(): void
    {
        $this->markTestSkipped();
        self::markTestSkipped();
        $this->tester->markTestSkipped();
    }
}
