<?php

declare(strict_types=1);

namespace Zept\unit;

use Codeception\Test\Unit as CodeceptionUnit;
use Zept\UnitTester;

class BaseUnitTest extends CodeceptionUnit
{
    protected UnitTester $tester;

    protected function _before(): void
    {

    }

    protected function _after(): void
    {

    }
}
