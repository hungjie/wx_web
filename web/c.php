<?php

//ini_set('display_errors', 1);
require_once("../app/frontend/config.php");
require_once("../lib/lib.php");
require_once("../lib/common.php");

$params = parse(urldecode($_SERVER['PATH_INFO']));
$config['PATH_INFO'] = join('/', $params);
session_start();
ob_start();
if (middleware_init()) {
    try {
        call($params) or $config['DEBUG'] or redirection("/error.html");
    } catch (Exception $e) {
        if ($config['DEBUG'])
            print_r($e);
        else
            redirection("/error.html");
    }
}
middleware_fini();

ob_end_flush();

// end
//--------function define-----------

/**
 * parse 把url按'/'，分割
 * $val: 如 /前缀/X/Y/a/b/c 的串
 * return: 数组，[0]为类名，[1]为方法名，第三个起，为参数
 *         如果 [1] == '', [1] = 'index', 即默认方法为index
 */
function parse($val) {

    $res = explode('/', trim($val, "/"), 3);

    $size = sizeof($res);
    if (($size == 0) || ($res[0] == ''))
        $res[0] = 'index';
    if ($size == 1)
        $res[1] = 'index';
    else if ($res[1] == '')
        $res[1] = 'index';
    return $res;
}

/**
 * $val: 数组，[0] 为类名，[1] 为方法名，[2] 为 a/b/c 形式的参数
 *
 */
function call($val) {
    global $config;

    if (!file_exists($config["base_dir"] . "/controller/" . $val[0] . "Controller.php"))
        return false;
    require_once($config["base_dir"] . "/controller/" . $val[0] . "Controller.php");
    $Classname = $val[0] . "Controller";
    $obj = new $Classname;

    $m = array($obj, $val[1]);
    $p = sizeof($val) == 3 ? explode('/', $val[2]) : array();

    $res = @call_user_func_array($m, $p);
    if ($res === false)
        return true;

    $theme_dir = dirname(__FILE__) . "/theme/";

    include_once($theme_dir . "default/theme_config.php");

    $theme_config = $theme_dir . $config['theme'] . "/theme_config.php";
    if (file_exists($theme_config))
        require_once($theme_config);


    $layout = $theme_dir . $config['theme'] . "/" . $config['layout'];
    if (!file_exists($layout))
        $layout = $theme_dir . "default/" . $config['layout'];
    require_once($layout);

    return true;
}

?>
