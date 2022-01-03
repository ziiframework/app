<?php

declare(strict_types=1);

// This file is used in the following environments: production, development and testing

$ztmp_components = $ztmp_components ?? (require __DIR__ . '/components.php');

$ztmp_config = [
    'class' => yii\console\Application::class,
    'id' => 'xxx_cli_application',
    'name' => 'xxx Cli Application',
    'controllerNamespace' => 'Zpp\Commands',
    'aliases' => [
        '@tests' => '@app/tests',
    ],
    'defaultRoute' => 'help',
    'controllerMap' => [
        'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'migrationTable' => '{{%dbmigration}}',
            // for migration without a namespace, `migrationPath` is required to specify the path
            'migrationPath' => [
                // '@yii/rbac/migrations',
                // '@yii/i18n/migrations',
            ],
            // for migrations that contain namespaces, `migrationNamespaces` is required to specify the path
            'migrationNamespaces' => [
                'Zpp\Migration',
                // 'yii\queue\db\migrations',
            ],
        ],
        // fixture generation command line.
        'fixture' => [
            'class' => yii\faker\FixtureController::class,
        ],
    ],
    'components' => [
        'errorHandler' => $ztmp_components[yii\console\ErrorHandler::class](),
        'request' => [
            'class' => yii\console\Request::class,
        ],
        'response' => [
            'class' => yii\console\Response::class,
        ],
    ],
];

unset($ztmp_config['class']);

return $ztmp_config;
