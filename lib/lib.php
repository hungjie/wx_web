<?php

require_once ('fbapi.php');

function core($corename) {
    global $config;
    $corename .= 'Core';
    require_once ($config['base_dir'] . "/core/$corename.php");
    return single($corename);
}

function pcore($corename) {
    if (isset($_SESSION['__core_fwr2sfdsf_' . $corename])){
        $return = $_SESSION['__core_fwr2sfdsf_' . $corename];
        if (method_exists($return, 'reinit')) $return->reinit();
        return $return;
    }
    return $_SESSION['__core_fwr2sfdsf_' . $corename] = core($corename);
}

function lib($file) {
    require_once ($file . '.php');
}

function middleware($middleware) {
    global $config;
    $middleware .= 'MiddleWare';
    require_once (dirname(__FILE__) . '/../middleware/' . $middleware . '.php');
    $m = new $middleware();
    $config['actived_middleware'][] = $m;
    return $m->init();
}

function middleware_init() {
    global $config;
    foreach ($config['middleware'] as $m => $p) {
        if (preg_match($p, $config['PATH_INFO']) == 1) {
            if (!middleware($m)) {
                return false;
            }
        }
    }
    return true;
}

function middleware_fini() {
    global $config;
    if (isset($config['actived_middleware']) && $config['actived_middleware']) {
        $middles = array_reverse($config['actived_middleware']);
        foreach ($middles as $m) {
            $m->fini();
        }
    }
}

function model($tablename) {
    require_once ( 'model.php');
    return new Model($tablename);
}

function fbapi() {
    if (isset($_SESSION['__get_Fbapi__']))
        $obj = $_SESSION['__get_Fbapi__'];
    if (is_a($obj, 'Fbapi'))
        return $obj;
    $obj = $_SESSION['__get_Fbapi__'] = new Fbapi();
    return $obj;
}

function get_db() {
    global $config;
    static $singledb;
    if (!empty($singledb))
        return $singledb;

    require_once 'db.php';
    extract($config['db_config']);

    $singledb = new DB($host, $db, $user, $password, null, $charset);

    return $singledb;
}

function form($id, $action, $ajax = false, $ajaxCallback = 'null', $ajaxResponeTarget = 'null', $class = '') {
    require_once 'form.php';
    return new Form($id, $action, $ajax, $ajaxCallback, $ajaxResponeTarget, $class);
}

function single($class) {
    static $objs;
    if (isset($objs[$class]))
        return $objs[$class];
    return $objs[$class] = new $class();
}

function session_cache($name, $value) {
    $_SESSION["session_cache.$name"] = serialize($value);
}

function get_session_cache($name) {
    if (!isset($_SESSION["session_cache.$name"]))
        return null;
    return unserialize($_SESSION["session_cache.$name"]);
}

function get_url($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($ch);
    return $content;
}

function __autoload($class_name) {
    global $config;
    $base_dir = $config['base_dir'];
    if (file_exists($base_dir . '/controller/' . $class_name . '.php'))
        require_once $base_dir . '/controller/' . $class_name . '.php';
    else if (file_exists($base_dir . '/core/' . $class_name . '.php'))
        require_once $base_dir . '/core/' . $class_name . '.php';
}

?>
