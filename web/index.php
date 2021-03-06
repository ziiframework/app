<?php

require __DIR__ . '/../env-local.php';
require __DIR__ . '/../env.php';

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/ziiframework/zii/src/Yii.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../config/base.php',
    require __DIR__ . '/../config/base-local.php',
    require __DIR__ . '/../config/web.php',
    require __DIR__ . '/../config/web-local.php'
);

(new yii\web\Application($config))->run();
