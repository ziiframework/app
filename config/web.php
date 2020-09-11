<?php declare(strict_types=1);

$cc_components = $cc_components ?? require __DIR__ . '/components.php';

$cc_config = [
    'class' => yii\web\Application::class,
    'id' => 'xxx_web_application',
    'name' => 'xxx',
    'controllerNamespace' => 'app\controllers',
    'defaultRoute' => 'index/index',
    'components' => [
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
    'as hostControl' => [
        'class' => yii\filters\HostControl::class,
        'allowedHosts' => [
            '*.xxx.com',
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
