<?php

declare(strict_types=1);

namespace Zept\acceptance;

use Zept\AcceptanceTester;

final class SiteCest extends BaseAcceptanceCest
{
    public function _before(AcceptanceTester $I): void
    {
        parent::_before($I);

        $I->amOnPage($this->prefixLang('/output-config'));
        // $I->makeHtmlSnapshot();

        $conf = <<<CONF
[language] => zh-CN
CONF;

        $this->seeEveryLineInSource($I, $conf);
    }

    protected function prefixLang(string $uri): string
    {
        $uri = parent::prefixLang($uri);

        return $uri;
    }

    public function gotoHome(AcceptanceTester $I): void
    {
        $I->assertEquals(self::HTTP_STATUS_200, $this->fetchUrlHttpStatusText($this->prefixDomain('/')));
        $I->assertEquals(self::HTTP_STATUS_200, $this->fetchUrlHttpStatusText($this->prefixDomain('')));

        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();

        $I->see('Congratulations', 'h1');
    }

    public function gotoLogin(AcceptanceTester $I): void
    {
        $I->assertEquals(self::HTTP_STATUS_200, $this->fetchUrlHttpStatusText($this->prefixDomain('/site/login')));

        $I->amOnPage('/site/login');
        $I->seeResponseCodeIsSuccessful();

        $I->see('Login', 'h1');
    }

    public function gotoContact(AcceptanceTester $I): void
    {
        $I->assertEquals(self::HTTP_STATUS_200, $this->fetchUrlHttpStatusText($this->prefixDomain('/site/contact')));

        $I->amOnPage('/site/contact');
        $I->seeResponseCodeIsSuccessful();

        $I->see('Contact', 'h1');
    }
}
