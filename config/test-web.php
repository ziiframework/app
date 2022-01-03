<?php declare(strict_types=1);

use Symfony\Component\Yaml\Yaml;

$ztmp_components = $ztmp_components ?? (require __DIR__ . '/components.php');

$ztmp_enable_strict_parsing = true;
if (isset($_SERVER['REQUEST_URI'])) {
    if (strpos($_SERVER['REQUEST_URI'], '/miniprogram/') === 0) {
        $ztmp_enable_strict_parsing = false;
    } else if (strpos($_SERVER['REQUEST_URI'], '/workbench/') === 0) {
        $ztmp_enable_strict_parsing = false;
    }
}

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/base.php',
    [
        'id' => 'xxx_test_web_application',
        'name' => 'xxx Test Web Application',
        'controllerNamespace' => 'Zpp\Controllers',
        'defaultRoute' => 'site/index',
        'components' => [
            'db' => $ztmp_components[yii\db\Connection::class]([
                'dsn' => 'mysql:' . implode(
                        ';',
                        [
                            'dbname=test0db',
                            'host=127.0.0.1',
                            'charset=utf8mb4',
                        ]
                    ) . ';',
                'username' => 'root',
                'password' => 'root12345',
            ]),
            'mailer' => $ztmp_components[yii\swiftmailer\Mailer::class]([
                'useFileTransport' => true,
            ]),
            'errorHandler' => $ztmp_components[yii\web\ErrorHandler::class](),
            'response' => $ztmp_components[yii\web\Response::class](),

            'urlManager' => $ztmp_components[yii\web\UrlManager::class]([
                'enableStrictParsing' => $ztmp_enable_strict_parsing,
                'web.rules' => array_merge(
                    require __DIR__ . '/routes_web.php',
                ),
            ]),
            'request' => $ztmp_components[yii\web\Request::class]([
                'cookieValidationKey' => 'testCookieValidationKey',
                'csrfCookie.secure' => false,
            ]),
            'session' => $ztmp_components[yii\web\DbSession::class]([
                'cookieParams.secure' => false,
            ]),
            'user' => $ztmp_components[yii\web\User::class]([
                'identityCookie.secure' => false,
            ]),
        ],
        'on beforeRequest' => static function (yii\base\Event $event): void {
            /** @var yii\web\Application $sender */
            $sender = $event->sender;

        },
        'on afterRequest' => static function (yii\base\Event $event): void {
            /** @var yii\web\Application $sender */
            $sender = $event->sender;

            $missing = $sender->getMissingTranslations();
            if (!YII_ENV_PROD && !empty($missing)) {
                foreach ($missing as $category => $texts) {
                    $po_file = ZDIR_ROOT . '/' . str_replace('_', '/', $category) . '.yml';

                    $po = Yaml::parseFile($po_file);
                    foreach (array_unique($texts) as $text) {
                        $po[$text][ZII_LANG_ALIAS[ZII_LANG_CURRENT]] = '-';
                    }

                    $result = [];
                    foreach ($po as $k => $v) {
                        foreach ($v as $kk => $vv) {
                            $result["T_ _T $k"][$kk] = "T_ _T $vv";
                        }
                    }

                    $dump = Yaml::dump($result, 2, 2);
                    $dump = str_replace("\n'T_ _T ", "\n\n'T_ _T ", $dump);
                    file_put_contents($po_file, str_replace('T_ _T ', '', $dump));
                }
            }
        },
        'as hostControl' => [
            'class' => yii\filters\HostControl::class,
            // see https://stackoverflow.com/questions/2814002/private-ip-address-identifier-in-regular-expression
            'allowedHosts' => [
                'yiitest.com',
                '*.yiitest.com',
            ],
        ],
    ]
);
