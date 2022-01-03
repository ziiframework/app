<?php declare(strict_types=1);

use Symfony\Component\Yaml\Yaml;

$ztmp_components = $ztmp_components ?? (require __DIR__ . '/components.php');

$ztmp_enable_strict_parsing = true;

$ztmp_config = [
    'class' => yii\web\Application::class,
    'id' => 'xxx_web_application',
    'name' => 'xxx',
    'controllerNamespace' => 'app\controllers',
    'defaultRoute' => 'site/index',
    'components' => [
        'errorHandler' => $ztmp_components[yii\web\ErrorHandler::class](),
        'response' => $ztmp_components[yii\web\Response::class](),
        'urlManager' => $ztmp_components[yii\web\UrlManager::class]([
            'enableStrictParsing' => $ztmp_enable_strict_parsing,
            'web.rules' => array_merge(
                require __DIR__ . '/routes_web.php',
            ),
        ]),
    ],
    'on beforeRequest' => static function (yii\base\Event $event): void {
        /** @var yii\web\Application $sender */
        $sender = $event->sender;

    },
    'on afterRequest' => static function (yii\base\Event $event): void {
        /** @var yii\web\Application $sender */
        $sender = $event->sender;

        $missing = $sender->getMissingTranslations();
        if (!YII_ENV_PROD && !empty($missing)) {
            foreach ($missing as $category => $texts) {
                $po_file = ZDIR_ROOT . '/' . str_replace('_', '/', $category) . '.yml';

                $po = Yaml::parseFile($po_file);
                foreach (array_unique($texts) as $text) {
                    $po[$text][ZII_LANG_ALIAS[ZII_LANG_CURRENT]] = '-';
                }

                $result = [];
                foreach ($po as $k => $v) {
                    foreach ($v as $kk => $vv) {
                        $result["T_ _T $k"][$kk] = "T_ _T $vv";
                    }
                }

                $dump = Yaml::dump($result, 2, 2);
                $dump = str_replace("\n'T_ _T ", "\n\n'T_ _T ", $dump);
                file_put_contents($po_file, str_replace('T_ _T ', '', $dump));
            }
        }
    },
    'as hostControl' => [
        'class' => yii\filters\HostControl::class,
        // see https://stackoverflow.com/questions/2814002/private-ip-address-identifier-in-regular-expression
        'allowedHosts' => YII_ENV_PROD ? [
            'xxx.com',
            '*.xxx.com',
        ] : array_merge(
            [
                'xxx.cc',
                '*.xxx.cc',

                '127.*',
                '10.*',
                '192.168.*',
            ],
            array_map(fn (int $p2): string => "172.$p2.*", range(16, 31)),
        ),
    ],
];

unset($ztmp_config['class']);

return $ztmp_config;
