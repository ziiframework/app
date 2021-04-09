<?php

use yii\web\UrlNormalizer;
use yii\web\UrlRule as WebUrlRule;

$ztmp_route_pattern_prefix = '';

$ztmp_routes_web = [
    ['route' => 'site/index', 'pattern' => ''],
    ['route' => 'site/about', 'pattern' => 'site/about'],
    ['route' => 'site/contact', 'pattern' => 'site/contact'],
    ['route' => 'site/error', 'pattern' => 'site/error'],
    ['route' => 'site/login', 'pattern' => 'site/login'],
    ['route' => 'site/captcha', 'pattern' => 'site/captcha'],
    ['route' => 'site/logout', 'pattern' => 'site/logout'],
];

foreach ($ztmp_routes_web as $ztmp_i => $ztmp_v) {
    $ztmp_is_Home = $ztmp_routes_web[$ztmp_i]['route'] === 'site/index';

    $ztmp_routes_web[$ztmp_i]['class'] = WebUrlRule::class;
    $ztmp_routes_web[$ztmp_i]['pattern'] = $ztmp_route_pattern_prefix . $ztmp_v['pattern'];

    $ztmp_routes_web[$ztmp_i]['normalizer'] = [
        'class' => UrlNormalizer::class,
        'action' => $ztmp_is_Home ? null : UrlNormalizer::ACTION_REDIRECT_PERMANENT,
    ];
}

return $ztmp_routes_web;
