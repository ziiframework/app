<?php

error_reporting(E_ALL);

defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/ziiframework/zii/src/Yii.php';

$config = require __DIR__ . '/../../config/test-cli.php';

unset($config['class']);

new yii\console\Application($config);
