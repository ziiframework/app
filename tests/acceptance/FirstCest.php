<?php

declare(strict_types=1);

namespace Cept\Acceptance;

use Cept\AcceptanceTester;

class FirstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
    }

    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Home');
    }
}
