<?php declare(strict_types=1);

$ztmp_components = $ztmp_components ?? (require __DIR__ . '/components.php');

$ztmp_enable_strict_parsing = true;

$ztmp_config = [
    'class' => yii\web\Application::class,
    'id' => 'xxx_web_application',
    'name' => 'xxx',
    'controllerNamespace' => 'app\controllers',
    'defaultRoute' => 'site/index',
    'components' => [
        'errorHandler' => $ztmp_components[yii\web\ErrorHandler::class](),
        'response' => $ztmp_components[yii\web\Response::class](),
        'urlManager' => $ztmp_components[yii\web\UrlManager::class]([
            'enableStrictParsing' => $ztmp_enable_strict_parsing,
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
    $ztmp_config['bootstrap'][] = 'debug';
    $ztmp_config['modules']['debug'] = [
        'class' => yii\debug\Module::class,
        'panels' => [
            'queue' => yii\queue\debug\Panel::class,
        ],
        'controllerNamespace' => 'yii\debug\controllers',
        'allowedIPs' => ['*'],
    ];
}

unset($ztmp_config['class']);

return $ztmp_config;
