<?php

$config = array(
    'DEBUG' => 1,
    'theme' => 'default'
    , 'layout' => 'layout.php'
    , 'js' => array(
        'http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js',
        '/asset/bootstrap-3.0.3/js/bootstrap.min.js',
    )
    , 'css' => array(
         '/asset/bootstrap-3.0.3/css/bootstrap.min.css',
         '/asset/bootstrap-3.0.3/css/bootstrap-theme.min.css',
    )
    , 'db_config' => array(
        'host' => '127.0.0.1'
        , 'db' => 'wx_sms'
        , 'user' => 'root'
        , 'password' => 'iloveu'
        , 'charset' => 'utf8'
    )
    , 'dom_ready' => array()
    , 'middleware' => array() //array('cachepage' => '/(^index\/index$|^d\/index$)/',)
    , 'host' => ''  // 'http://' . $_SERVER['HTTP_HOST'] . '/'
    , 'host_special' => ''  // 'http://' . $_SERVER['HTTP_HOST'] . '/'
    , 'meta_site_name' => ''
);

$config['base_dir'] = dirname(__FILE__);
$config['title'] = "wx";
$config['description'] = "wx";
$config['keywords'] = "wx";

date_default_timezone_set("Asia/Shanghai");
?>
