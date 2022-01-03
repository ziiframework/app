<?php

declare(strict_types=1);

// This file is used in the following environments: production, development and testing

use Symfony\Component\Yaml\Yaml;

$ztmp_components = $ztmp_components ?? (require __DIR__ . '/components.php');

if (!isset($ztmp_secrets) || !is_array($ztmp_secrets)) {
    $ztmp_secrets = [];
    if (file_exists(__DIR__ . '/secrets.php')) {
        $ztmp_secrets = require __DIR__ . '/secrets.php';
    }
}

$ztmp_config = [
    'class' => yii\base\Application::class,
    'basePath' => ZDIR_ROOT,
    'vendorPath' => ZDIR_ROOT . '/vendor',
    'runtimePath' => ZDIR_SHARED . '/runtime',
    'viewPath' => ZDIR_ROOT . '/pages',
    'layoutPath' => ZDIR_ROOT . '/pages',
    'layout' => 'layout.php',
    'language' => ZII_LANG_ALIAS[ZII_LANG_CURRENT],
    'sourceLanguage' => ZII_LANG_ALIAS[ZII_LANG_DEFAULT],
    'bootstrap' => ['log', 'queue'],
    'aliases' => [
        '@Zpp/Migration' => ZDIR_ROOT . '/src/Migration',
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'timeZone' => 'Asia/Shanghai',
    'version' => '1.0.0',
    'params' => array_merge([], $ztmp_secrets),
    'components' => [
        'cache' => $ztmp_components[yii\caching\DbCache::class](),
        'log' => $ztmp_components[yii\log\Dispatcher::class](),
        'formatter' => $ztmp_components[yii\i18n\Formatter::class](),
        'i18n' => $ztmp_components[yii\i18n\I18N::class]([
            'translations' => [
                't_*' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'sourceLanguage' => 'zh-CN',
                    'basePath' => '@app/lang',
                    'fileMap' => [
                        't_pool' => 'pool.php',
                        't_dbf' => 'dbf.php',
                    ],
                    'on missingTranslation' => static function (yii\i18n\MissingTranslationEvent $event): void {
                        $zpp = Yii::$app;

                        if ($zpp instanceof yii\web\Application) {
                            $po = Yaml::parseFile(ZDIR_ROOT . '/' . str_replace('_', '/', $event->category) . '.yml');

                            if (!isset($po[$event->message][ZII_LANG_ALIAS[ZII_LANG_CURRENT]])) {
                                $zpp->putMissingTranslations($event->category, $event->message);
                            }
                        }
                    },
                ],
            ],
        ]),
        'queue' => $ztmp_components[yii\queue\db\Queue::class](),
        'authManager' => $ztmp_components[yii\rbac\DbManager::class](),
    ],
    'modules' => [
    ],
];

unset($ztmp_config['class']);

return $ztmp_config;
