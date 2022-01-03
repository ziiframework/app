<?php

declare(strict_types=1);

namespace Zept\acceptance;

use InvalidArgumentException;
use Zept\AcceptanceTester;

class BaseAcceptanceCest
{
    public const HTTP_STATUS_200 = 'HTTP/1.1 200 OK';
    public const HTTP_STATUS_301 = 'HTTP/1.1 301 Moved Permanently';
    public const HTTP_STATUS_404 = 'HTTP/1.1 404 Not Found';
    public const HTTP_STATUS_403 = 'HTTP/1.1 403 Forbidden';

    public function _before(AcceptanceTester $I): void
    {
        $I->amOnPage($this->prefixLang('/output-config'));

        $conf = <<<CONF
[basePath] => /var/www/repos/yiitest/3
[vendorPath] => /var/www/repos/yiitest/3/vendor
[runtimePath] => /var/www/repos/yiitest/shared/runtime
[layoutPath] => /var/www/repos/yiitest/3/pages
[viewPath] => /var/www/repos/yiitest/3/pages
[layout] => layout.php
[sourceLanguage] => zh-CN
[timeZone] => Asia/Shanghai

[dsn] => mysql:dbname=test0db;host=127.0.0.1;charset=utf8mb4;
[username] => root
[password] => root12345

[domain] => app.yiitest.com

[sameSite] => Lax

[csrfParam] => _csrf
[cookieValidationKey] => testCookieValidationKey

[useFileTransport] => 1

[id] => xxx_test_web_application
[name] => xxx Test Web Application

[allowedHosts] => Array
[0] => yiitest.com
[1] => *.yiitest.com
CONF;

        $this->seeEveryLineInSource($I, $conf);
    }

    protected function prefixDomain(string $uri): string
    {
        return 'http://app.yiitest.com' . $uri;
    }

    protected function prefixLang(string $uri): string
    {
        if (mb_strpos($uri, '/') !== 0) {
            throw new InvalidArgumentException("Uri must be started with /");
        }

        return $uri;
    }

    protected function fetchUrlHttpStatusText(string $url)
    {
        // By default get_headers uses a GET request to fetch the headers.
        $headers = get_headers($url, PHP_MAJOR_VERSION >= 8 ? true : 1);

        return is_array($headers) && isset($headers[0]) ? $headers[0] : null;
    }

    protected function fetchUrlContentType(string $url)
    {
        // By default get_headers uses a GET request to fetch the headers.
        $headers = get_headers($url, PHP_MAJOR_VERSION >= 8 ? true : 1);

        return is_array($headers) && isset($headers['Content-Type']) ? $headers['Content-Type'] : null;
    }

    protected function fetchUrlContentLength(string $url)
    {
        // By default get_headers uses a GET request to fetch the headers.
        $headers = get_headers($url, PHP_MAJOR_VERSION >= 8 ? true : 1);

        return is_array($headers) && isset($headers['Content-Length']) ? $headers['Content-Length'] : null;
    }

    protected function fetchUrlRedirectLocation(string $url)
    {
        // By default get_headers uses a GET request to fetch the headers.
        $headers = get_headers($url, PHP_MAJOR_VERSION >= 8 ? true : 1);

        return is_array($headers) && isset($headers['Location']) ? $headers['Location'] : null;
    }

    protected function seeEveryLineInSource(AcceptanceTester $I, string $lines): void
    {
        $src = $I->grabPageSource();

        foreach (pf_split_string_using_rn($lines) as $line) {
            if (pf_is_string_filled($line)) {
                // reduce low-level mistakes
                if (mb_strpos_utf8($line, 'www.xxx.test') !== false) {
                    $line = str_replace('www.xxx.test', 'app.yiitest.com', $line);
                    $I->comment('www.xxx.test has been replaced by app.yiitest.com');
                    $I->markTestIncomplete();
                }

                $I->assertStringContainsString($line, $src);
            }
        }
    }

    protected function dontSeeAnyLineInSource(AcceptanceTester $I, string $lines): void
    {
        foreach (pf_split_string_using_rn($lines) as $line) {
            if (pf_is_string_filled($line)) {
                $I->dontSeeInSource($line);
            }
        }
    }

    protected function seeCanonicalEquals(AcceptanceTester $I, string $canonical): void
    {
        $src = $I->grabPageSource();

        $result =
            (mb_strpos($src, "<link rel=\"canonical\" href=\"$canonical\" />", 0, 'UTF-8') !== false) ||
            (mb_strpos($src, "<link href=\"$canonical\" rel=\"canonical\" />", 0, 'UTF-8') !== false)
        ;

        $I->assertTrue($result);
    }
}
