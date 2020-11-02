<?php declare(strict_types=1);

$cc_components = $cc_components ?? [];

/**
 * 缓存组件（db驱动）.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\caching\DbCache::class] = static function (array $config = []): array {
    return array_merge([
        'class' => yii\caching\DbCache::class,
        'cacheTable' => '{{%dbcache}}',
        'keyPrefix' => 'gche_',
    ], $config);
};

/**
 * 数据库组件.
 *
 * 配置示例：
 * [
 *   'dsn' => 'mysql:dbname=example;host=IP;charset=utf8mb4;',
 *   'username' => 'root',
 *   'password' => 'example',
 *   'tablePrefix' => '',
 * ]
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\db\Connection::class] = static function (array $config) use ($cc_components): array {
    return array_merge([
        'class' => yii\db\Connection::class,
        'attributes' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // 错误报告模式：exception
//            PDO::ATTR_CASE => PDO::CASE_NATURAL, // 保留数据库驱动返回的列名
//            PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL, // 空字符串<=>NULL：保持原样
//            PDO::ATTR_STRINGIFY_FETCHES => false, // 提取的时候将数值转换为字符串：No
//            PDO::ATTR_TIMEOUT => 5, // 数据库连接超时时间：5s
//            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true, // 使用缓冲查询
        ],
        'enableSchemaCache' => true,
        'schemaCache' => $cc_components[yii\caching\DbCache::class]([
            'keyPrefix' => 'dbsh_',
        ]),
        'enableQueryCache' => true,
        'queryCache' => $cc_components[yii\caching\DbCache::class]([
            'keyPrefix' => 'dbqe_',
        ]),
        'charset' => 'utf8mb4',
        'serverStatusCache' => $cc_components[yii\caching\DbCache::class]([
            'keyPrefix' => 'dbst_',
        ]),
        'enableLogging' => !YII_ENV_PROD, // 生产环境提升性能 https://github.com/yiisoft/yii2/issues/12528
        'enableProfiling' => !YII_ENV_PROD, // 生产环境提升性能 https://github.com/yiisoft/yii2/issues/12528
    ], $config);
};

/**
 * Cookie组件.
 *
 * 配置示例：
 * [
 *   'name' => '_csrf',
 *   'secure' => true,
 * ]
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\web\Cookie::class] = static function (array $config): array {
    if (isset($config['secure']) && is_bool($config['secure'])) {
        $secure = $config['secure'];
    } else {
        $secure = YII_ENV === 'prod';
    }

    unset($config['secure']);

    return array_merge([
        'class' => yii\web\Cookie::class,
        'name' => $config['name'],
        'expire' => time() + 7200,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? '',
        'httpOnly' => true,
        'secure' => $secure,
        'sameSite' => yii\web\Cookie::SAME_SITE_LAX,
    ], $config);
};

/**
 * 日志输出组件（file驱动）.
 *
 * @param string $level 日志记录等级
 * @return array 合并后的配置数组
 */
$cc_components[yii\log\FileTarget::class] = static function (string $level): array {
    return [
        'class' => yii\log\FileTarget::class,
        'except' => [
            'yii\web\ForbiddenHttpException:*',
            'yii\web\HttpException:403',
            'yii\web\HttpException:401',
            'yii\web\HttpException:404',
        ],
        'levels' => [$level],
        'microtime' => true,
        'enableRotation' => false,
        'logFile' => sprintf('@runtime/logs/%s_%s.log', $level, date('Ym')),
        'maxFileSize' => 102400, // 100M
    ];
};

/**
 * 日志组件.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\log\Dispatcher::class] = static function (array $config = []) use ($cc_components): array {
    return array_merge([
        'class' => yii\log\Dispatcher::class,
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => YII_DEBUG ? [
            $cc_components[yii\log\FileTarget::class]('info'),
            $cc_components[yii\log\FileTarget::class]('warning'),
            $cc_components[yii\log\FileTarget::class]('error'),
        ] : [
            $cc_components[yii\log\FileTarget::class]('warning'),
            $cc_components[yii\log\FileTarget::class]('error'),
        ],
    ], $config);
};

/**
 * 格式化组件.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\i18n\Formatter::class] = static function (array $config = []): array {
    return array_merge([
        'class' => yii\i18n\Formatter::class,
        'defaultTimeZone' => 'Asia/Shanghai',
    ], $config);
};

/**
 * 国际化组件.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\i18n\I18N::class] = static function (array $config = []): array {
    return array_merge([
        'class' => yii\i18n\I18N::class,
        'translations' => [
            'app' => [
                'class' => yii\i18n\PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => '@app/langfiles',
                'fileMap' => [
                    'app' => 'app.php',
                    'app/error' => 'error.php',
                ],
            ],
        ],
    ], $config);
};

/**
 * 缓存组件（file驱动）.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\caching\FileCache::class] = static function (array $config = []): array {
    return array_merge([
        'class' => yii\caching\FileCache::class,
        'keyPrefix' => 'fcc_',
    ], $config);
};

/**
 * 错误处理组件（web）.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\web\ErrorHandler::class] = static function (array $config = []): array {
    return array_merge([
        'class' => yii\web\ErrorHandler::class,
        'displayVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION'],
    ], $config);
};

/**
 * 错误处理组件（console）.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\console\ErrorHandler::class] = static function (array $config = []): array {
    return array_merge([
        'class' => yii\console\ErrorHandler::class,
    ], $config);
};

/**
 * 请求处理组件（web）.
 *
 * 配置示例：
 * [
 *   'cookieValidationKey' => 'testCookieValidationKey',
 *   'csrfCookie.secure' => true,（可选，默认是true）
 * ]
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\web\Request::class] = static function (array $config) use ($cc_components): array {
    if (!isset($config['cookieValidationKey'])) {
        throw new yii\base\InvalidConfigException('cookieValidationKey must be set.');
    }

    if (isset($config['csrfCookie.secure']) && is_bool($config['csrfCookie.secure'])) {
        $secure = $config['csrfCookie.secure'];
    } else {
        $secure = YII_ENV === 'prod';
    }

    unset($config['csrfCookie.secure']);

    $config = array_merge([
        'class' => yii\web\Request::class,
        'parsers' => [
            'application/json' => yii\web\JsonParser::class,
            'text/json' => yii\web\JsonParser::class,
        ], // TODO 增加 XmlParser
        'isConsoleRequest' => false,
        'csrfCookie' => $cc_components[yii\web\Cookie::class]([
            'name' => '_csrf',
            'secure' => $secure,
        ]),
        'csrfParam' => '_csrf', // 必须与 csrfCookie.name 相同
    ], $config);

    return $config;
};

/**
 * 响应处理组件（web）.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\web\Response::class] = static function (array $config = []): array {
    return array_merge([
        'class' => yii\web\Response::class,
        'formatters' => [
            yii\web\Response::FORMAT_HTML => [
                'class' => yii\web\HtmlResponseFormatter::class,
                'contentType' => 'text/html',
            ],
            yii\web\Response::FORMAT_JSON => [
                'class' => yii\web\JsonResponseFormatter::class,
                'contentType' => 'application/json; charset=utf-8',
                'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                'prettyPrint' => !YII_ENV_PROD,
            ],
            yii\web\Response::FORMAT_XML => [
                'class' => yii\web\XmlResponseFormatter::class,
                'contentType' => 'application/xml',
                'version' => '1.0',
                'encoding' => 'UTF-8',
                'rootTag' => 'xml',
                'itemTag' => 'item',
            ],
        ],
        'format' => yii\web\Response::FORMAT_HTML,
        'charset' => 'UTF-8',
        'on beforeSend' => static function ($event): void {
            /** @var $response yii\web\Response */
            $response = $event->sender;

            /**
             * 处理返回header头.
             */
            $contentType = Yii::$app->getRequest()->getContentType();
            $contentType = $contentType ?? 'text/html; charset=UTF-8';
            $comaPosition = strpos($contentType, ';');
            if ($comaPosition !== false) {
                $contentType = substr($contentType, 0, $comaPosition);
            }
            if ($contentType === 'application/json' || $contentType === 'text/json') {
                $response->format = yii\web\Response::FORMAT_JSON;
            } elseif ($contentType === 'application/xml' || $contentType === 'text/xml') {
                $response->format = yii\web\Response::FORMAT_XML;
            }

            if ($response->format === yii\web\Response::FORMAT_HTML) {
                $originalData = $response->data;
                if (is_array($originalData)) {
                    $response->data = yii\helpers\Json::encode($originalData);
                }
            }
            if ($response->format === yii\web\Response::FORMAT_JSON) {
                $originalData = $response->data;
                if (empty(Yii::$app->getRequest()->get('no_optimization'))) {
                    $response->data = [
                        'isOk' => $response->getIsSuccessful(),
                        'retCode' => $response->getStatusCode(),
                    ];

                    $toUrl = $response->getHeaders()->get('Location');
                    if ($toUrl !== null) {
                        $response->getHeaders()->remove('Location');
                        $response->data['toUrl'] = $toUrl;
                    }

                    $response->data['data'] = $originalData;
                    if ($response->data['isOk']) {
                        if (isset($originalData['err'])) {
                            $response->data['isOk'] = false;
                            $response->data['retCode'] = 400;
                        }
                    }

                    $response->setStatusCode(200);
                } else {
                    // for UEditor only TODO remove
                    if (Yii::$app->getRequest()->get('reformat') === 'ueditor') {
                        $response->format = yii\web\Response::FORMAT_HTML;
                        if (is_array($response->data)) {
                            $response->data = yii\helpers\Json::encode($response->data);
                        }
                    }
                }
            }
        },
    ], $config);
};

/**
 * 会话控制组件（DB驱动）.
 *
 * 配置示例：
 * [
 *   'cookieParams.secure' => true,（可选，默认为true）
 * ]
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\web\DbSession::class] = static function (array $config = []): array {
    if (isset($config['cookieParams.secure']) && is_bool($config['cookieParams.secure'])) {
        $secure = $config['cookieParams.secure'];
    } else {
        $secure = YII_ENV === 'prod';
    }

    unset($config['cookieParams.secure']);

    $config = array_merge([
        'class' => yii\web\DbSession::class,
        'sessionTable' => '{{%dbsession}}',
        'timeout' => 7200,
        'useCookies' => true,
        'cookieParams' => [
            'lifetime' => 7200,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'] ?? '',
            'httpOnly' => true,
            'secure' => $secure,
            'sameSite' => yii\web\Cookie::SAME_SITE_LAX
        ],
    ], $config);

    return $config;
};

/**
 * 用户组件.
 *
 * 配置示例：
 * [
 *   'identityCookie.secure' => true,（可选，默认为true）
 * ]
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\web\User::class] = static function (array $config = []) use ($cc_components): array {
    if (isset($config['identityCookie.secure']) && is_bool($config['identityCookie.secure'])) {
        $secure = $config['identityCookie.secure'];
    } else {
        $secure = YII_ENV === 'prod';
    }

    unset(
        $config['identityCookie.secure'],
        $config['identityCookie.name']
    );

    $config = array_merge([
        'class' => yii\web\User::class,
        'autoRenewCookie' => true,
        'enableAutoLogin' => true,
        'enableSession' => true,
        'identityClass' => app\models\User::class,
        'identityCookie' => $cc_components[yii\web\Cookie::class]([
            'name' => '_identity',
            'secure' => $secure,
        ]),
        'loginUrl' => ['site/login'],
//        'on afterLogin' => static function (yii\web\UserEvent $event): void {
//            if ($event->isValid) {
//
//            }
//        },
    ], $config);

    return $config;
};

/**
 * 消息队列组件（数据库驱动）.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\queue\db\Queue::class] = static function (array $config = []): array {
    return array_merge([
        'class' => yii\queue\db\Queue::class,
        'tableName' => '{{%dbqueue}}',
        'channel' => 'default',
        'mutex' => yii\mutex\MysqlMutex::class,
        'attempts' => 3,
        'ttr' => 300, // 秒
        'as log' => yii\queue\LogBehavior::class,
    ], $config);
};

/**
 * 邮件组件.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\swiftmailer\Mailer::class] = static function (array $config = []): array {
    return yii\helpers\ArrayHelper::merge([
        'class' => yii\swiftmailer\Mailer::class,
        'viewPath' => '@app/mail',
        'htmlLayout' => '@app/mail/layouts/html',
        'textLayout' => '@app/mail/layouts/text',
        'useFileTransport' => false,
        'transport' => [
            'class' => Swift_SmtpTransport::class,
            'timeout' => 45,
        ],
        'messageConfig' => [
            'charset' => 'UTF-8',
        ],
    ], $config);
};

/**
 * RBAC权限组件（数据库驱动）.
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\rbac\DbManager::class] = static function (array $config = []) use ($cc_components): array {
    return array_merge([
        'class' => yii\rbac\DbManager::class,
        'itemTable' => '{{%dbauth_item}}',
        'itemChildTable' => '{{%dbauth_item_child}}',
        'assignmentTable' => '{{%dbauth_assignment}}',
        'ruleTable' => '{{%dbauth_rule}}',
        'cache' => $cc_components[yii\caching\DbCache::class]([
            'keyPrefix' => 'rbac_',
        ]),
    ], $config);
};

/**
 * 路由控制组件.
 *
 * 配置示例：
 * [
 *   'rest.rules' => [
        [
            'class' => yii\rest\UrlRule::class,
            'pluralize' => false,
            'controller' => [
                ...
            ],
        ],
 *   ]
 * ]
 *
 * @param array $config 配置数组
 * @return array 合并后的配置数组
 */
$cc_components[yii\web\UrlManager::class] = static function (array $config) use ($cc_components): array {
    $config = array_merge([
        'class' => yii\web\UrlManager::class,
        'cache' => $cc_components[yii\caching\DbCache::class]([
            'keyPrefix' => 'uri_',
        ]),
        'enablePrettyUrl' => true,
        'enableStrictParsing' => false,
        'showScriptName' => false,
        'ruleConfig' => [
            'class' => yii\web\UrlRule::class,
        ],
        'rules' => [
            ...(empty($config['web.rules']) ? [] : $config['web.rules']),
            ...(empty($config['rest.rules']) ? [] : $config['rest.rules']),
        ],
    ], $config);

    unset($config['web.rules'], $config['rest.rules']);

    return $config;
};

return $cc_components;
