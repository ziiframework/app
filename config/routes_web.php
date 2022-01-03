<?php

use yii\web\UrlNormalizer;
use yii\web\UrlRule as WebUrlRule;

// implementation (with the trailing slash):
// 1. for the homepage, if ZII_LANG_CURRENT == ZII_LANG_DEFAULT, then pattern = ""                 ignore the UrlNormalizer
// 2. for the homepage, if ZII_LANG_CURRENT != ZII_LANG_DEFAULT, then pattern = "/LANG/"           ignore the UrlNormalizer
// 3. for the non-homepage, if ZII_LANG_CURRENT == ZII_LANG_DEFAULT, then pattern = "/URI"         use the UrlNormalizer
// 4. for the non-homepage, if ZII_LANG_CURRENT != ZII_LANG_DEFAULT, then pattern = "/LANG/URI"    use the UrlNormalizer

$ztmp_route_pattern_prefix = '';
if (ZII_LANG_CURRENT !== ZII_LANG_DEFAULT) {
    // prefer to use ZII_URI_NODES[0] for case compatibility, such as en, EN etc.
    if (strcasecmp(ZII_LANG_CURRENT, ZII_URI_NODES[0]) === 0) {
        $ztmp_route_pattern_prefix = '/' . ZII_URI_NODES[0];
    } else {
        $ztmp_route_pattern_prefix = '/' . ZII_LANG_CURRENT;
    }
}

// keep in sync with waf.lua here
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
    $ztmp_routes_web[$ztmp_i]['pattern'] = $ztmp_route_pattern_prefix . ($ztmp_is_Home && ZII_LANG_CURRENT !== ZII_LANG_DEFAULT ? '/' : '') . $ztmp_v['pattern'];

    $ztmp_routes_web[$ztmp_i]['normalizer'] = [
        'class' => UrlNormalizer::class,
        'action' => $ztmp_is_Home ? null : UrlNormalizer::ACTION_REDIRECT_PERMANENT,
    ];
}

return $ztmp_routes_web;
