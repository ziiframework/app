<?php

// This file is used in the following environments: production, development and testing

$cc_components = $cc_components ?? require __DIR__ . '/components.php';

if (!isset($cc_params) || !is_array($cc_params)) {
    $cc_params = [];
    if (file_exists(__DIR__ . '/params.php')) {
        $cc_params = require __DIR__ . '/params.php';
    }
}

$cc_config = [
    'class' => yii\base\Application::class,
    'basePath' => dirname(__DIR__),
    'layout' => 'default',
    'language' => 'zh-CN', // current language
    'sourceLanguage' => 'en-US', // source language
    'bootstrap' => ['log', 'queue'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'timeZone' => 'Asia/Shanghai',
    'params' => array_merge([], $cc_params),
    'version' => '1.0.0',
    'components' => [
        'cache' => $cc_components[yii\caching\DbCache::class](),
        'log' => $cc_components[yii\log\Dispatcher::class](),
        'formatter' => $cc_components[yii\i18n\Formatter::class](),
        'i18n' => $cc_components[yii\i18n\I18N::class](),
        'queue' => $cc_components[yii\queue\db\Queue::class](),
        'authManager' => $cc_components[yii\rbac\DbManager::class](),
    ],
    'modules' => [

    ],
];

$cc_config['vendorPath'] = $cc_config['basePath'] . '/vendor';
$cc_config['runtimePath'] = $cc_config['basePath'] . '/runtime';
$cc_config['viewPath'] = $cc_config['basePath'] . '/views';
$cc_config['layoutPath'] = $cc_config['viewPath'] . '/layouts';

unset($cc_config['class']);

return $cc_config;
