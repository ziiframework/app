<?php

declare(strict_types=1);

namespace Zept\api;

use Zept\ApiTester;

class DemoApiCest extends BaseApiCest
{
    public function mockHelloWorld(ApiTester $I): void
    {
        $I->markTestSkipped();
    }
}
