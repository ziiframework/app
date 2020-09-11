<?php declare(strict_types=1);

$cc_components = $cc_components ?? require __DIR__ . '/components.php';

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/base.php',
    require __DIR__ . '/test-local.php',
    [
        'id' => 'xxx_test_web_application',
        'name' => 'xxx Test Web Application',
        'language' => 'en-US',
        'controllerNamespace' => 'app\controllers',
        'defaultRoute' => 'site/index',
        'components' => [
            'request' => $cc_components[yii\web\Request::class]([
                'cookieValidationKey' => 'testCookieValidationKey',
                'csrfCookie.secure' => false,
            ]),
            'session' => $cc_components[yii\web\DbSession::class]([
                'cookieParams.secure' => false,
            ]),
            'user' => $cc_components[yii\web\User::class]([
                'identityCookie.secure' => false,
            ]),
            'mailer' => $cc_components[yii\swiftmailer\Mailer::class]([
                'useFileTransport' => true,
            ]),
            'errorHandler' => $cc_components[yii\web\ErrorHandler::class](),
            'response' => $cc_components[yii\web\Response::class](),
            'urlManager' => $cc_components[yii\web\UrlManager::class]([
                'rest.rules.controller' => [
                    'keep/placeholder',
                ],
            ]),
        ],
        'on beforeRequest' => static function (): void {
        },
    ]
);
