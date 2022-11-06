<?php

declare(strict_types=1);

namespace Tests\Api;

use Tests\Support\ApiTester;

class DemoApiCest extends BaseApiCest
{
    public function mockHelloWorld(ApiTester $I): void
    {
        $I->markTestSkipped();
    }
}
