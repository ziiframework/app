<?php

/*
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}
*/

require __DIR__ . '/../env-test.php';
require __DIR__ . '/../env.php';

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/ziiframework/zii/src/Yii.php';

$config = require __DIR__ . '/../config/test-web.php';

(new yii\web\Application($config))->run();
