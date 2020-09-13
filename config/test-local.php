<?php

$cc_components = $cc_components ?? require __DIR__ . '/components.php';

return [
    'components' => [
        'db' => $cc_components[yii\db\Connection::class]([
            'dsn' => 'mysql:' . implode(
                    ';',
                    [
                        'dbname=testdb',
                        'host=localhost',
                        'charset=utf8mb4',
                    ]
                ) . ';',
            'username' => 'root',
            'password' => 'root12345',
        ]),
        'mailer' => $cc_components[yii\swiftmailer\Mailer::class]([
            'useFileTransport' => true,
        ]),
    ]
];
