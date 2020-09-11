<?php

$cc_components = $cc_components ?? require __DIR__ . '/components.php';

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/base.php',
    require __DIR__ . '/test-local.php',
    [
        'class' => yii\console\Application::class,
        'id' => 'xxx_test_cli_application',
        'name' => 'xxx Test Cli Application',
        'language' => 'en-US',
        'controllerNamespace' => 'app\commands',
        'aliases' => [
            '@tests' => '@app/tests',
        ],
        'defaultRoute' => 'help',
        'controllerMap' => [
            'migrate' => [
                'class' => yii\console\controllers\MigrateController::class,
                // for migration without a namespace, `migrationPath` is required to specify the path
                'migrationPath' => [
                    // '@yii/rbac/migrations',
                    // '@yii/i18n/migrations',
                ],
                // for migrations that contain namespaces, `migrationNamespaces` is required to specify the path
                'migrationNamespaces' => [
                    'app\migrations',
                    // 'yii\queue\db\migrations',
                ],
            ],
            // fixture generation command line.
            'fixture' => [
                'class' => yii\faker\FixtureController::class,
            ],
        ],
        'components' => [
            'errorHandler' => $cc_components[yii\console\ErrorHandler::class](),
            'request' => [
                'class' => yii\console\Request::class,
            ],
            'response' => [
                'class' => yii\console\Response::class,
            ],
            'mailer' => $cc_components[yii\swiftmailer\Mailer::class]([
                'useFileTransport' => true,
            ]),
        ],
    ]
);
