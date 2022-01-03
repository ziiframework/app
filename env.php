<?php

defined('ZDIR_SHARED') or define('ZDIR_SHARED', dirname(__DIR__) . '/shared');

defined('ZDIR_ROOT') or define('ZDIR_ROOT', __DIR__);

// for ajax request (https://stackoverflow.com/questions/8163703/cross-domain-ajax-doesnt-send-x-requested-with-header)
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'XMLHttpRequest') === 0) {
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400'); // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        }
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }
        exit(0);
    }
}

$ztmp_Uri_Nodes = [];
if (isset($_SERVER['REQUEST_SCHEME'], $_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI'])) {
    $ztmp_Url_Full = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $ztmp_Url_Path = parse_url($ztmp_Url_Full,PHP_URL_PATH);

    if (is_string($ztmp_Url_Path)) {
        $ztmp_Url_Path = trim(rawurldecode($ztmp_Url_Path));
        foreach (explode('/', $ztmp_Url_Path) as $ztmp_Uri_Node) {
            $ztmp_Uri_Node = trim($ztmp_Uri_Node);
            if ($ztmp_Uri_Node !== '') {
                $ztmp_Uri_Nodes[] = $ztmp_Uri_Node;
            }
        }
    }
}
defined('ZII_URI_NODES') or define('ZII_URI_NODES', $ztmp_Uri_Nodes);


defined('ZII_LANG_ALL') or define('ZII_LANG_ALL', [
    'zh-hans' => '中文（简体）',
    'zh-hant' => '中文（繁體）',
    'en' => 'English',
]);
defined('ZII_LANG_ALIAS') or define('ZII_LANG_ALIAS', [
    'zh-hans' => 'zh-CN',
    'zh-hant' => 'zh-TW',
    'en' => 'en',
]);

defined('ZII_LANG_DEFAULT') or define('ZII_LANG_DEFAULT', 'zh-hans');

/*
 * define ZII_LANG_CURRENT
 *
 * below logic is case insensitive.
 */
if (isset(ZII_URI_NODES[0])) {
    $ztmp_Uri_Node_0_lowercase = strtolower(ZII_URI_NODES[0]);
    $ztmp_Uri_Node_0_indexOf = array_search($ztmp_Uri_Node_0_lowercase, array_keys(array_change_key_case(ZII_LANG_ALL, CASE_LOWER)), true);

    if (is_int($ztmp_Uri_Node_0_indexOf) && $ztmp_Uri_Node_0_indexOf >= 0) {
        defined('ZII_LANG_CURRENT') or define('ZII_LANG_CURRENT', array_keys(ZII_LANG_ALL)[$ztmp_Uri_Node_0_indexOf]);
    } else {
        defined('ZII_LANG_CURRENT') or define('ZII_LANG_CURRENT', ZII_LANG_DEFAULT);
    }
} else {
    defined('ZII_LANG_CURRENT') or define('ZII_LANG_CURRENT', ZII_LANG_DEFAULT);
}

/*
 * Normalize Uri.
 */
if (isset(ZII_URI_NODES[0]) && strcasecmp(ZII_LANG_CURRENT, ZII_URI_NODES[0]) === 0) {
    /*
     * Case 1: `/zh-hans/...` -> `/...`
     */
    if (ZII_LANG_CURRENT === ZII_LANG_DEFAULT) {
        $ztmp_Uri_Q = isset($_SERVER['QUERY_STRING']) && is_string($_SERVER['QUERY_STRING']) && (trim($_SERVER['QUERY_STRING']) !== '') ? '?' . trim($_SERVER['QUERY_STRING']) : '';
        $ztmp_Nodes_without_0 = ZII_URI_NODES;
        unset($ztmp_Nodes_without_0[0]);

        header(
            'Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/' . implode('/', $ztmp_Nodes_without_0) . $ztmp_Uri_Q,
            true,
            301
        );
        exit;
    }

    /*
     * Case 2: `/EN/...` -> `/en/...`  Does it make sense?
     */
}
