<?php

declare(strict_types=1);

namespace Tests\Api;

use Tests\Support\ApiTester;

class BaseApiCest
{
    public function _before(ApiTester $I): void
    {
    }

    protected function expectJsonResponseWithCode200(ApiTester $I): void
    {
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['isOk' => true, 'retCode' => 200]);
    }
}
