<?php

require __DIR__ . '/../env-test.php';
require __DIR__ . '/../env.php';

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/ziiframework/zii/src/Yii.php';

$config = require __DIR__ . '/../config/test-web.php';

(new yii\web\Application($config))->run();
