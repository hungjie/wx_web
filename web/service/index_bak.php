<?php

define('MAIN_PATH', dirname(__FILE__));

function __error_log($msg, $path = '', $line = '') {
  $logFile = date('Y-m-d').'_error.txt';
  $logFile = MAIN_PATH.'/log/'.$logFile;
  $msg = $line.':'.basename($path).'('.date('Y-m-d H:i:s').') >>> ' . $msg . "\r\n";
  file_put_contents($logFile, $msg, FILE_APPEND);
}

if($_GET['echostr']){
  echo $_GET['echostr'];
  exit();
}


$data = file_get_contents("php://input");
if ($data){
  $date = time();
  __error_log($data, __FILE__, __LINE__);
  $input = simplexml_load_string ($data);
  switch($input->MsgType){
  case 'event':
    if ($input->Event == 'subscribe') {
      $res = <<<ETO
<xml>
	<ToUserName><![CDATA[{$input->FromUserName}]]></ToUserName>
	<FromUserName><![CDATA[{$input->ToUserName}]]></FromUserName>
 <CreateTime>$date</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[hello xiaoliu
		   1 demo 1
		   2 demo 2
		   3 demo 3]]></Content>
 </xml>
ETO;
    }
    break;
  default:
    break;
  }
  echo $res;
}


?>