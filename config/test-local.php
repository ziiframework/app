<?php

$cc_components = $cc_components ?? require __DIR__ . '/components.php';

return [
    'components' => [
        'db' => $cc_components[yii\db\Connection::class]([
            'dsn' => 'mysql:' . implode(
                    ';',
                    [
                        'dbname=xxx',
                        'host=rm-xxx.mysql.rds.aliyuncs.com',
                        'charset=utf8mb4',
                    ]
                ) . ';',
            'username' => 'xxx',
            'password' => base64_encode('xxx') . 'xxx',
        ]),
        'mailer' => $cc_components[yii\swiftmailer\Mailer::class]([
            'useFileTransport' => true,
        ]),
    ]
];
