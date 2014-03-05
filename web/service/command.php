<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once MAIN_PATH . '/session.php';

class command {

    function command() {
    }

    function get_main_shortcode() {
        global $config;

        return $config['shortcode_usm'] . $config['shortcode_min'];
    }

    function geturl($url, $method = 'get', $header = null, $postdata = null, $new = false, $timeout = 15) {
        $s = curl_init();

        curl_setopt($s, CURLOPT_URL, $url);

        if ($header) {
//            curl_setopt($s, CURLOPT_HEADER, true);
            curl_setopt($s, CURLOPT_HTTPHEADER, $header);
        }

//        curl_setopt($s, CURLOPT_VERBOSE, TRUE);
        
        curl_setopt($s, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($s, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($s, CURLOPT_MAXREDIRS, 5);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);

        if (strtolower($method) == 'post') {
            curl_setopt($s, CURLOPT_POST, true);
            curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
        } else if (strtolower($method) == 'delete') {
            curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'DELETE');
        } else if (strtolower($method) == 'put') {
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
        }

        //curl_setopt($s, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.3) Gecko/20100401'); //'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);

        $html = curl_exec($s);
        //$status = curl_getinfo($s/* , CURLINFO_HTTP_CODE */);
        //print_r($html);
        //print_r($status);

        curl_close($s);
        return $html;

        //return array($html, $status);
    }

    //type : reg,main
    function getURLContent($msisdn, &$shortcode, $msg, $type = "", $extral = array()) {
        $this->msisdn = $msisdn;
        /*
          global $config;
          $shortcode = $config['shortcode_usm'] . $shortcode;
         * 
         */

        global $config;

        $url = $config['back_addr'] . '/mt.php?';

        $url_array = array('service' => 'fb');
        $url_array['mno'] = $msisdn;
        $url_array['shortcode'] = $shortcode;
        $url_array['txt'] = $msg;

        if (!empty($type)) {
            $url_array['action'] = strtoupper($type);
        }

        if (!empty($extral) && count($extral) > 0) {
            $url_array = array_merge($url_array, $extral);
        }

        $url .= http_build_query($url_array);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $info = "start send to usm->" . $msg . "\n" . "$msisdn/$shortcode\n$url";
        __info_log($info, __FILE__, __LINE__);

        if (preg_match('/XDEBUG_TRACE/', $_SERVER['QUERY_STRING'])) {
            echo '<pre>';
            print_r($info);
            echo '</pre>';
        }

        $content = curl_exec($ch);
        //echo $content;
        //echo $msg;

        return $content;
    }

    function getUSSDContent($msisdn, $shortcode, $msg, $type = "", $extral = array()) {
        $this->msisdn = $msisdn;
        global $config;

        $url = $config['back_addr'] . '/ussd.php?';

        $url_array = array();
        $url_array['msisdn'] = $msisdn;
        $url_array['from'] = $shortcode;
        $url_array['msg'] = $msg;

        $url .= http_build_query($url_array);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $info = "start send to usm->" . $msg . "\n" . "$msisdn/$shortcode\n$url";
        __info_log($info, __FILE__, __LINE__);

        $content = curl_exec($ch);

        echo $content;
    }

    function is_menu_shortcode($shortcode) {
        global $config;
        $shortcode_msg_min = $config['shortcode_min'] + $config['shortcode_menu_count'];
        $cur_shortcode = preg_replace("/" . $config['shortcode_usm'] . "/", '', $shortcode, 1);

        if (intval($cur_shortcode) <= $shortcode_msg_min)
            return true;

        return false;
    }

    function is_message_shortcode($shortcode) {
        global $config;
        $shortcode_msg_min = $config['shortcode_min'] + $config['shortcode_menu_count'];
        $cur_shortcode = preg_replace("/" . $config['shortcode_usm'] . "/", '', $shortcode, 1);

        if (intval($cur_shortcode) > $shortcode_msg_min)
            return true;
        return false;
    }
    
    function formateMenuArray(&$menu, $cur_shortcode, $last_shortcode) {
        $menu['last_shortcode'] = $last_shortcode;
        $menu['cur_shortcode'] = $cur_shortcode;

        $_SESSION['cur_shortcode'] = $cur_shortcode;

        return $menu;
    }
    
    function wx_sms_content($msisdn, $shortcode, $menu_str){
        $time = time();
        
        $sms = <<<ETO
<xml>
        <ToUserName><![CDATA[$msisdn]]></ToUserName>
        <FromUserName><![CDATA[{$_SESSION['own_user_name']}]]></FromUserName>
 <CreateTime>$time</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[$menu_str]]></Content>
 </xml>
ETO;
        
        echo $sms;
    }
    
    function wx_mutiple_sms_content($msisdn, $shortcode, $menu_str){
        $time = time();
        
        $menu_content = "";
        foreach($menu_str as $menu){
            $menu_content .= "<item>";
            $temp = <<<ETO
<Title><![CDATA[{$menu['Title']}]]></Title> 
<Description><![CDATA[{$menu['Description']}]]></Description>
<PicUrl><![CDATA[{$menu['PicUrl']}]]></PicUrl>
<Url><![CDATA[{$menu['Url']}]]></Url>
ETO;
            $menu_content .= $temp;
            $menu_content .= "</item>";
        }
        
        $count = count($menu_str);
        
        $sms = <<<ETO
<xml>
        <ToUserName><![CDATA[$msisdn]]></ToUserName>
        <FromUserName><![CDATA[{$_SESSION['own_user_name']}]]></FromUserName>
 <CreateTime>$time</CreateTime>
 <MsgType><![CDATA[news]]></MsgType>
 <ArticleCount>$count</ArticleCount>
 <Articles>
 $menu_content
</Articles>
 </xml>
ETO;
        __error_log($sms,__FILE__,__LINE__);
        echo $sms;
    }

    function returnMessageAndSaveAction($msisdn, &$shortcode, $menu_str
    , $action
    , $input_type = "text") {
        $_SESSION['last_shortcode'] = $shortcode;

        $sessionDB = new sessionDB($msisdn, $shortcode);
        $sessionDB->setSessionObject($msisdn, $action, $shortcode);

//        return $this->getURLContent($msisdn, $shortcode, $menu_str);
        switch($input_type){
            case 'text':
                return $this->wx_sms_content($msisdn, $shortcode, $menu_str);
                break;
            case 'mutiple':
                return $this->wx_mutiple_sms_content($msisdn, $shortcode, $menu_str);
                break;
        }
        
        return $this->wx_sms_content($msisdn, $shortcode, $menu_str);
    }
    
    function returnMessageAndNotSaveAction($msisdn, &$shortcode, $menu_str
    , $action
    , $input_type = "text") {
        $_SESSION['last_shortcode'] = $shortcode;

//        $sessionDB = new sessionDB($msisdn, $shortcode);
//        $sessionDB->setSessionObject($msisdn, $action, $shortcode);

//        return $this->getURLContent($msisdn, $shortcode, $menu_str);
        switch($input_type){
            case 'text':
                return $this->wx_sms_content($msisdn, $shortcode, $menu_str);
                break;
            case 'mutiple':
                return $this->wx_mutiple_sms_content($msisdn, $shortcode, $menu_str);
                break;
        }
        
        return $this->wx_sms_content($msisdn, $shortcode, $menu_str);
    }

    function returnUSSDMessageAndSaveAction($msisdn, &$shortcode
    , $menu_array, $action) {
        $_SESSION['last_shortcode'] = $shortcode;

        $sessionDB = new sessionDB($msisdn, $shortcode);
        $sessionDB->setSessionObject($msisdn, $action, $shortcode);

        $this->formateMenuArray($menu_array
                , $shortcode, $_SESSION['last_shortcode']);

        echo json_encode($menu_array);

        return true;
    }

    function getDirNO($msisdn) {
        global $config;
        $val = intval($msisdn);

        $mod = $val % $config['group_num'];

        return $mod;
    }

   
}

?>
