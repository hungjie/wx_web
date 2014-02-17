<?php

$config = array(
    'theme' => 'bootstrap'
    , 'layout' => 'admin_layout.php'
    , 'js' => array('/asset/jquery.js'
        , '/asset/jq_ui/js/jquery-ui-1.8.12.custom.min.js'
        , '/asset/jq_ui/js/jquery.ui.datepicker-zh-CN.js'
        , '/asset/jquery-validate/jquery.validate.pack.js'
        , '/asset/jquery-validate/localization/messages_cn.js'
        , '/asset/tinymce/jscripts/tiny_mce/tiny_mce.js'
        , '/asset/jquery.cookie.js'
        , '/asset/jquery.MultiFile.pack.js'
        , '/asset/jquery.form.js'
    )
    , 'css' => array(
        '/asset/jq_ui/css/cupertino/jquery-ui-1.8.12.custom.css'
    )
    , 'db_config' => array(
        'host' => 'localhost'
        , 'db' => 'tongji'
        , 'user' => 'tongji'
        , 'password' => '12345678'
        , 'charset' => null
    )
    ,'dom_ready' => array()
);

$config['base_dir'] = dirname(__FILE__);
$config['upload_path'] = $config['base_dir'] . "/../web/image/";
$config['upload_path_nums'] = 9;

$config['description'] = 'description';
$config['keywords'] = 'keywords';
$config['title'] = 'tongji admin';

$config['domain'] = array(
    1=>1500,
    2=>2500,
    3=>3000);


$config['commission'] = "30";

$config['domain_status'] = array(
    0=>'一般域名',
    1=>'试用',
    2=>'未审核',
    3=>'已审核',
    4=>'续费申请'
);
        
$config['table_page_count'] = 20;

$config['balance_log_type'] = array(
    0=>'计费记录',
    1=>'分成记录'
);

$config['balance_log_status'] = array(
    0=>'未处理',
    1=>'已处理'
);

$config['serial_type']= array(
    0=>'15个月',
    1=>'8个月'
);

$config['serial_status']=array(
    1=>'新增序列',
    2=>'已用序列'
);

date_default_timezone_set("Asia/Shanghai");
?>
