<?php

declare(strict_types=1);

if (!isset($_SERVER['REQUEST_SCHEME']) || $_SERVER['REQUEST_SCHEME'] !== 'http') {
    die('You are not allowed to access this file.');
}

if (!in_array($_SERVER['SERVER_NAME'] ?? null, ['app.yiitest.com', 'yiitest.com'], true)) {
    die('You are not allowed to access this file.');
}

error_reporting(-1);

require __DIR__ . '/../env-test.php';
require __DIR__ . '/../env.php';

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/ziiframework/zii/src/Yii.php';

$ztmp_config = require __DIR__ . '/../config/test-web.php';

if (in_array($_SERVER['REQUEST_URI'] ?? null, ['/output-config', '/output-config'], true)) {
    print_r($ztmp_config);
    exit;
}

(new yii\web\Application($ztmp_config))->run();
