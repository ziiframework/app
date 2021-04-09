<?php

if (!isset($_SERVER['REQUEST_SCHEME']) || $_SERVER['REQUEST_SCHEME'] !== 'http') {
    die('You are not allowed to access this file.');
}

if (!isset($_SERVER['SERVER_NAME']) || $_SERVER['SERVER_NAME'] !== 'app.yiitest.com') {
    die('You are not allowed to access this file.');
}

error_reporting(-1);

require __DIR__ . '/../env-test.php';
require __DIR__ . '/../env.php';

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/ziiframework/zii/src/Yii.php';

$ztmp_config_for_testing = require __DIR__ . '/../config/test-web.php';

if (
    isset($_SERVER['REQUEST_URI'])
    && in_array($_SERVER['REQUEST_URI'], ['/output-config', '/output-config'], true)
) {
    print_r($ztmp_config_for_testing);
    exit;
}

(new yii\web\Application($ztmp_config_for_testing))->run();
