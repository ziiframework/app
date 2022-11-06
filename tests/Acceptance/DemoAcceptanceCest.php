<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Tests\AcceptanceTester;

final class DemoAcceptanceCest
{
    // more hooks see: https://codeception.com/docs/06-ModulesAndHelpers#hooks
    public function _before(AcceptanceTester $I): void
    {
    }

    public function helloWorld(AcceptanceTester $I): void
    {
        $I->markTestSkipped();
    }
}
