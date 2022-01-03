<?php declare(strict_types=1);

$ztmp_components = $ztmp_components ?? (require __DIR__ . '/components.php');

$cc_enable_strict_parsing = true;

$cc_config = [
    'class' => yii\web\Application::class,
    'id' => 'xxx_web_application',
    'name' => 'xxx',
    'controllerNamespace' => 'app\controllers',
    'defaultRoute' => 'site/index',
    'components' => [
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
        'allowedHosts' => YII_ENV_PROD ? [
            '*.xxx.com',
        ] : [
            '*.xxx.test',
        ],
    ],
];

if (YII_ENV_DEV) {
    $cc_config['bootstrap'][] = 'debug';
    $cc_config['modules']['debug'] = [
        'class' => yii\debug\Module::class,
        'panels' => [
            'queue' => yii\queue\debug\Panel::class,
        ],
        'controllerNamespace' => 'yii\debug\controllers',
        'allowedIPs' => ['*'],
    ];
}

unset($cc_config['class']);

return $cc_config;
