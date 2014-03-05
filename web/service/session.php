<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once MAIN_PATH . '/menu.php';
require_once MAIN_PATH . '/event_menu.php';
require_once MAIN_PATH . '/db.php';
require_once MAIN_PATH.'/msisdn_infos.php';

class sessionDB {

    var $msisdnProcess;
    var $shortcode;
    var $msisdn;

    function sessionDB($msisdn, $shortcode) {
        $this->msisdnProcess = new msisdnProcess();
        $this->shortcode = $shortcode;
        $this->msisdn = $msisdn;
    }

    function getSessionObject($msisdn, $shortcode = '') {
        $string = $this->getSessionValue($msisdn);
        if ($string) {
            $string = base64_decode($string);
            $string = gzuncompress($string);
            return unserialize($string);
        }

        return false;
    }

    function setSessionObject($msisdn, $object, &$shortcode) {
        $session_value = serialize($object);
        $session_value = gzcompress($session_value);
        $session_value = base64_encode($session_value);

        $this->setSessionValue($msisdn, $session_value, $shortcode);
    }

    function setSessionObjectShortcode($msisdn, $object, &$shortcode) {
        $shortcode = $_SESSION['shortcode'];

        $session_value = serialize($object);
        $session_value = gzcompress($session_value);
        $session_value = base64_encode($session_value);

        $this->setSessionValueShortcode($msisdn, $session_value, $shortcode);
    }

    function setSessionValueShortcode($msisdn, $session_value, $shortcode) {
//        $msisdnProcess = new msisdnProcess();
//        $msisdnProcess->set_cur_session_code($msisdn, $shortcode);

        $db = get_db();

        $db->begin_query();

        $db->table('user_session')
                ->where(array('msisdn' => $msisdn, 'shortcode' => $shortcode));

        $res = $db->exec();

        $db->__table = 'user_session';

        if ($res->count() > 0) {
            $db->update(array('msisdn' => $msisdn, 'shortcode' => $shortcode), array('session_value' => $session_value));
        } else {
            $db->insert(array('msisdn' => $msisdn, 'shortcode' => $shortcode, 'session_value' => $session_value));
        }
    }

    function getSessionValue($msisdn, $shortcode = '') {
        $db = get_db();
        $db->begin_query();

        $db->table('user_session')
                ->where(array('msisdn' => $msisdn, 'shortcode' => $shortcode))
        ;

        $res = $db->exec();

        if ($res && $res->next())
            return $res->__data['session_value'];

        return false;
    }

    function get_new_shortcode() {
        global $config;
        //$cur_shortcode = $_SESSION['cur_shortcode'];

//        $msisdnProcess = new msisdnProcess();
//        $cur_shortcode = $msisdnProcess->get_cur_session_code($this->msisdn);
        //echo $cur_shortcode;
        $cur_shortcode = $_SESSION['cur_shortcode'];

        if (!$cur_shortcode) {
            return $config['shortcode_usm'] . $config['shortcode_min'];
        }

        $cur_shortcode = preg_replace("/" . $config['shortcode_usm'] . "/", '', $cur_shortcode, 1);
        //echo '<br>' . $cur_shortcode;

        if ($cur_shortcode >= $config['shortcode_menu_count'] + $config['shortcode_min']) {
            $cur_shortcode = $config['shortcode_min'];
        } else {
            $cur_shortcode = $cur_shortcode + 1;
        }

        return $config['shortcode_usm'] . $cur_shortcode;
    }

    function setSessionValue($msisdn, $session_value, &$shortcode) {
        $shortcode = $this->get_new_shortcode();

//        $msisdnProcess = new msisdnProcess();
//        if ($shortcode == $_SESSION['shortcode']) {
//            $msisdnProcess->set_cur_session_code($msisdn, $shortcode);
//            $shortcode = $this->get_new_shortcode();
//        }
//        $msisdnProcess->set_cur_session_code($msisdn, $shortcode);
        $_SESSION['cur_shortcode'] = $shortcode;

        $db = get_db();

        $db->begin_query();

        $db->table('user_session')
                ->where(array('msisdn' => $msisdn, 'shortcode' => $shortcode));

        $res = $db->exec();

        $db->__table = 'user_session';

        if ($res->count() > 0) {
            $db->update(array('msisdn' => $msisdn, 'shortcode' => $shortcode), array('session_value' => $session_value));
        } else {
            $db->insert(array('msisdn' => $msisdn, 'shortcode' => $shortcode, 'session_value' => $session_value));
        }
    }
}

class userSession extends sessionDB {
    var $command;
    var $main_menu;
    function userSession($msisdn, $shortcode) {
        $this->command = new command();
        $this->main_menu = new order_infos();
        
        if(empty($shortcode)){
            global $config;
            if(!empty($_SESSION['cur_shortcode']))
                $shortcode = $_SESSION['cur_shortcode'];
            else
                $shortcode = $config['shortcode_usm'].$config['shortcode_min'];
        }
        
        parent::sessionDB($msisdn, $shortcode);
    }

    function getURLContent($msisdn, &$shortcode, $msg) {
        return $this->command->getURLContent($msisdn, $shortcode, $msg, $this->return_type);
    }

    function tempAction($action, $fb_id, $message) {
        $res = true;

        if (empty($action->action)) {
            $res = $action->action($message, $fb_id, $this->msisdn, $this->shortcode);
        } else {
            if ($action->next_action === false) {
                $res = $action->do_action($action->action, array($fb_id,
                    $this->msisdn,
                    $this->shortcode,
                    $message)
                );
            } else {
                $res = $action->action($message, $fb_id, $this->msisdn, $this->shortcode);
            }
        }

        if ($res === false || $res === null) {
            if ($_SESSION['ussd_request']) {
                $this->main_menu->showOwnerList($fb_id, $this->msisdn
                        , $this->shortcode, $message);
            } else {
                $this->main_menu->showOwnerList($fb_id, $this->msisdn
                        , $this->shortcode, $message);
            }
        }

        return $res;
    }

    function action($message) {
        $value = $this->getSessionValue($this->msisdn, $this->shortcode);
        $sessionArray = array();
        
        if($message == 'mainmenu'){
            $this->main_menu->showOwnerList($fb_id, $this->msisdn
                        , $this->shortcode, $message);
            return;
        }

        if ($value && !empty($value)) {
            $sessionArray = base64_decode($value);
            $sessionArray = gzuncompress($sessionArray);
            $sessionArray = unserialize($sessionArray);

            if (is_object($sessionArray)) {
                //temp menu
                $this->tempAction($sessionArray, $fb_id, $message);
                return;
            }
        }
        
        $this->main_menu->showOwnerList($fb_id, $this->msisdn
                        , $this->shortcode, $message);
    }

}

?>
