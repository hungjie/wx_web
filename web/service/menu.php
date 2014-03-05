<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once MAIN_PATH . '/command.php';
require_once MAIN_PATH . '/menu_str.php';
require_once MAIN_PATH . '/session.php';
require_once MAIN_PATH . '/msisdn_infos.php';

class menu {
    var $list_command;
//    var $parameters;
    var $msisdnInfo;
    var $action;
    var $next_action;

    var $menu_str;
    
    var $is_asyn_sent;
    
    function prepare_title($title){return $title;}
    function prepare_item($item){return $item;}
    function prepare_input($input){return $input;}
    
    function menu($pre_menu = NULL, $extarl = NULL) {
    
        $this->list_command = array();

        $this->action = NULL;
        $this->next_action = false;

        $this->msisdnInfo = new msisdnProcess();
        
        $this->is_asyn_sent = true;
        
        $this->initMenu();
    }

    function initMenu() {
        $this->action = "showOwnerList";
    }

    function get_class_menu($class) {
        global $_menu_str_;

        $class_name = get_class($class);
        if (isset($_menu_str_[$class_name])) {
            $item = $_menu_str_[$class_name]['item'];
            return $class->prepare_item($item);
        }

        return false;
    }

    function get_class_asyn_menu($custom = false) {
        global $_menu_str_;

        $class_name = get_class($this);

        $custom_str = "";

        if ($custom) {
            $custom_str = "_$custom";
        }

        return $_menu_str_['dynamic']['action']["ussd_$class_name$custom_str"];
    }

    function return_asyn_menu_and_continue($fb_command, $msisdn
    , $shortcode, $custom = false
    , $my_command = false) {
        $asyn_menu = $this->get_class_asyn_menu($custom);

        $this->list_command = array();

        if (!$my_command)
            $this->list_command[1] = new fb_back_main();
        else {
            $this->list_command[$my_command['key']] = $my_command['value'];
        }

        $this->init_ussd_menu_str();

        $this->menu_array['title'] = $asyn_menu;

        if ($my_command) {
            $this->menu_array['menu_list'][$my_command['key']] = $my_command['name'];
        }

        $this->next_action = true;

        $fb_command->returnUSSDMessageAndSaveAction($msisdn, $shortcode
                , $this->menu_array, $this);

        $this->is_asyn_sent = true;
    }

    function init_ussd_menu_str($need_input = false, $input_type = "text") {
        global $_menu_str_;
        $cn = get_class($this);

        if (isset($_menu_str_[$cn])) {
            $title = "";
            if (isset($_menu_str_[$cn]["title"])) {
                $title = $this->prepare_title($_menu_str_[$cn]["title"]);
                
            }

            $this->menu_array['title'] = $title;
            $this->menu_array['menu_list'] = array();

            $last_key = false;
            if (count($this->list_command) > 0) {
                foreach ($this->list_command as $key => $class) {
                    $menu = $this->get_class_menu($class);

                    $this->menu_array['menu_list'][$key] = $menu;
                    $last_key = $key;
                }
            }

            if (isset($_menu_str_[$cn]["input"])) {
                $this->menu_array['need_input'] = $this->prepare_input($_menu_str_[$cn]["input"]);
            } else if ($need_input) {
                $this->menu_array['need_input'] = $need_input;
            } else {
                $this->menu_array['need_input'] = false;
            }

            $this->menu_array['input_type'] = $input_type;
            $this->menu_array['main_menu'] = $last_key;

            return true;
        }

        return false;
    }

    function init_menu_str($para = '') {
        global $_menu_str_;
        $cn = get_class($this);
        if (empty($para)) {
            if (isset($_menu_str_[$cn]) && isset($_menu_str_[$cn]['menu'])) {
                $this->menu_str = $_menu_str_[$cn]['menu'];
            }
        } else {
            if (isset($_menu_str_[$cn]) && isset($_menu_str_[$cn]['action'][$para])) {
                $this->menu_str = $_menu_str_[$cn]['action'][$para];
            }
        }

        return $this->menu_str;
    }

    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        return false;
    }

    function do_action($function_name, $command_paras = array()) {
        if (empty($function_name)) {
            return false;
        }

        $res = call_user_func_array(array($this, $function_name), $command_paras);

        return $res;
    }

    //for session action same as temp_menu class.
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        return $this->int_action(strval($input->Content), $fb_id, $msisdn, $shortcode);
    }

    function base_action($input, $fb_id, $msisdn, $shortcode, $object) {

        if ($object->next_action == true) {
            return $object->action($input, $fb_id, $msisdn, $shortcode);
        } else {
            if (!empty($object->action)) {
                return $object->do_action($object->action
                                , array($fb_id, $msisdn, $shortcode, $input));
            }

            return $object->action($input, $fb_id, $msisdn, $shortcode);
        }
        return true;
    }

    function int_action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        if (is_numeric($input)) {
            $num = intval($input);
            $object = $this->list_command[$num];
            if ($object) {
                return $this->base_action($input, $fb_id, $msisdn, $shortcode, $object);
            }
        }

        $wrong_command_menu = new main_menu();

        $wrong_command_menu->showOwnerList($fb_id, $msisdn, $shortcode, $input);
        return true;
//        return false;
    }

    function return_ussd_message($msisdn, &$shortcode, $next_action = true, $need_input=false, $input_type="text") {
        $this->init_ussd_menu_str($need_input, $input_type);

        if ($next_action === true) {
            $this->next_action = true;
        }
        else {
            if ($next_action) {
                $this->action = $next_action;
            }
        }

        $ussd_command = new command();
        $ussd_command->returnUSSDMessageAndSaveAction($msisdn, $shortcode, $this->menu_array, $this);

        return true;
    }

}

class test extends menu{
    function test(){
        parent::menu();
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = array(
            array(
                'Title'=>"这是一个测试",
                'Description'=>"描述1",
                'PicUrl'=>"https://mp.weixin.qq.com/cgi-bin/getimgdata?token=379077712&msgid=10000000&mode=large&source=file&fileId=10000000",
                'Url'=>"http://news.xinhuanet.com/world/2013-09/08/c_125345393.htm",
            ),
            array(
                'Title'=>"这是第二个测试",
                'Description'=>"描述2",
                'PicUrl'=>"https://mp.weixin.qq.com/cgi-bin/getimgdata?token=379077712&msgid=10000000&mode=large&source=file&fileId=10000000",
                'Url'=>"http://linux.chinaunix.net/techdoc/desktop/2006/11/15/944062.shtml",
            ),
        );
        
        $this->next_action = true;
        
        $command = new command();
        $command->returnMessageAndSaveAction($msisdn, $shortcode
                , $menu_str, $this, 'mutiple');
        
        return true;
    }
}

require_once MAIN_PATH . '/order.php';
require_once MAIN_PATH . '/address.php';

class main_menu2 extends menu{
    function main_menu2(){
        parent::menu();
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
        
        $this->list_command[1] = new start_order();
        $this->list_command[2] = new order_infos();
        
        $this->list_command[3] = new view_orders();
        $this->list_command[4] = new manage_your_address();
        
        $this->next_action = true;
        
        $command = new command();
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        return $this->int_action($input, $fb_id, $msisdn, $shortcode);
    }
}

class main_menu extends menu{
    function main_menu(){
        parent::menu();
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
        
        $this->list_command[1] = new start_info();
        $this->list_command[2] = new view_info();
        
        $this->next_action = true;
        
        $command = new command();
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        return $this->int_action($input, $fb_id, $msisdn, $shortcode);
    }
}

class start_info extends menu{
    function start_info(){
        parent::menu();
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
    }
}

class view_info extends menu{
    function start_info(){
        parent::menu();
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
    }
}

?>