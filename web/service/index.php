<?php

require_once 'config.php';

require_once MAIN_PATH . '/session.php';
require_once MAIN_PATH . '/event_menu.php';

if ($_GET['echostr']) {
    echo $_GET['echostr'];
    __error_log('========='.$_GET['echostr'].'=========', __FILE__, __LINE__);
    exit();
}

$data = file_get_contents("php://input");

/*
$data = <<<eto
<xml><ToUserName><![CDATA[testsms]]></ToUserName>
<FromUserName><![CDATA[34567]]></FromUserName>
<CreateTime>123456677</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[unsubscribe]]></Event>
<EventKey><![CDATA[EVENTKEY]]></EventKey>
</xml>
eto;
//*/


/*$data = <<<eto
<xml><ToUserName><![CDATA[testsms]]></ToUserName>
<FromUserName><![CDATA[34567]]></FromUserName>
<CreateTime>123456677</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[1]]></Content>
</xml>
eto;
*/

if ($data) {
    __error_log($data, __FILE__, __LINE__);
    $input = simplexml_load_string($data);
    
    session_id("user" . $input->FromUserName);
    session_start();
    
    $_SESSION['from_user_name'] = $input->FromUserName;

    $_SESSION['own_user_name'] = strval($input->ToUserName);

    switch ($input->MsgType) {
        case 'event':
            $msisdnProcess = new msisdnProcess();
            if ($input->Event == 'subscribe') {
//                $res = <<<ETO
//<xml>
//        <ToUserName><![CDATA[{$input->FromUserName}]]></ToUserName>
//        <FromUserName><![CDATA[{$input->ToUserName}]]></FromUserName>
// <CreateTime>$date</CreateTime>
// <MsgType><![CDATA[text]]></MsgType>
// <Content><![CDATA[你好，欢迎关注心开开
// 回复【1】 办事指南
// 回复【2】 预约办事
// 回复【0】 主菜单]]></Content>
// </xml>
//ETO;
                try {
                    $msisdnProcess->add_user(strval($input->FromUserName));
                    $session = new userSession(strval($input->FromUserName), '');
                    $session->action('mainmenu');
                } catch (Exception $e) {
                    echo $e->getMessage();
                    __error_log($e->getMessage(), __FILE__, __LINE__);
                }
            }else if($input->Event == 'unsubscribe'){
                $msisdnProcess->set_user_status(strval($input->FromUserName), 0);
            }else if($input->Event == 'CLICK'){
                try{
                $event_menu = new event_menu();
                $event_menu->action(strval($input->EventKey),'', strval($input->FromUserName));
                } catch (Exception $e) {
                    echo $e->getMessage();
                    __error_log($e->getMessage(), __FILE__, __LINE__);
                }
            }

            break;
        case 'text':
            try {
                $session = new userSession(strval($input->FromUserName), '');
                $session->action(strval($input->Content));
            } catch (Exception $e) {
                echo $e->getMessage();
                __error_log($e->getMessage(), __FILE__, __LINE__);
            }
            break;
        default:
            break;
    }
}
?>
