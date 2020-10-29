<?php

declare(strict_types=1);

namespace Zept\acceptance;

use Zept\AcceptanceTester;

class FirstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
    }

    public function homePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->see('Home');
    }

    public function loginPageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/site/login');
        $I->seeResponseCodeIsSuccessful();
        $I->see('Login');
    }

    public function contactPageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/site/contact');
        $I->seeResponseCodeIsSuccessful();
        $I->see('Contact');
    }
}
