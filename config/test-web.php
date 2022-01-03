<?php declare(strict_types=1);

$ztmp_components = $ztmp_components ?? (require __DIR__ . '/components.php');

$cc_enable_strict_parsing = true;

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/base.php',
    [
        'id' => 'xxx_test_web_application',
        'name' => 'xxx Test Web Application',
        // 'bootstrap' => ['debug'],
        'controllerNamespace' => 'app\controllers',
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
            'mailer' => $ztmp_components[yii\swiftmailer\Mailer::class]([
                'useFileTransport' => true,
            ]),
            'errorHandler' => $ztmp_components[yii\web\ErrorHandler::class](),
            'response' => $ztmp_components[yii\web\Response::class](),

            'urlManager' => $ztmp_components[yii\web\UrlManager::class]([
                'enableStrictParsing' => $cc_enable_strict_parsing,
                'web.rules' => array_merge(
                    require __DIR__ . '/routes_web.php',
                ),
            ]),
        ],
        'on beforeRequest' => static function (): void {
        },
        'as hostControl' => [
            'class' => yii\filters\HostControl::class,
            'allowedHosts' => [
                '*.yiitest.com',
            ],
        ],
        'modules' => [
//            'debug' => [
//                'class' => yii\debug\Module::class,
//                'panels' => [
//                    'queue' => yii\queue\debug\Panel::class,
//                ],
//                'controllerNamespace' => 'yii\debug\controllers',
//                'allowedIPs' => ['*'],
//            ],
        ],
    ]
);
