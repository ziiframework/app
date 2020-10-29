<?php

if (!isset($_SERVER['REQUEST_SCHEME']) || $_SERVER['REQUEST_SCHEME'] !== 'http') {
    die('You are not allowed to access this file.');
}

if (!isset($_SERVER['SERVER_NAME']) || $_SERVER['SERVER_NAME'] !== 'app.yiitest.com') {
    die('You are not allowed to access this file.');
}

require __DIR__ . '/../env-test.php';
require __DIR__ . '/../env.php';

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/ziiframework/zii/src/Yii.php';

$config = require __DIR__ . '/../config/test-web.php';

(new yii\web\Application($config))->run();
