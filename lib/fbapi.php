<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Fbapi {

    var $app_id = '249897905153088';
    var $app_secret = '07f15f611d79b82e23abaa32d73d55f0';
    var $my_url = 'http://www.digtaobao.com/fblogin/';

    function Fbapi() {
        
    }

    function is_login() {
        if ($this->access_token && $this->expire > time() && $this->id()){
            // $_SESSION['id'] = $this->id();
            return true;
        }
        else {
            unset($_SESSION['id']);
            return false;
        }
    }

    function login() {
        session_regenerate_id(true);

        $this->access_token = null;

        $code = $_REQUEST["code"];

        if (empty($code)) {
            $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
            $dialog_url = "https://www.facebook.com/dialog/oauth?client_id="
                    . $this->app_id . "&redirect_uri=" . urlencode($this->my_url) . "&state="
                    . $_SESSION['state']
                . '&scope=' . 'email';
            ;

//            echo("<script> top.location.href='" . $dialog_url . "'</script>");
            redirection($dialog_url);
        } else {

            if ($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
                $token_url = "https://graph.facebook.com/oauth/access_token?"
                        . "client_id=" . $this->app_id . "&redirect_uri=" . urlencode($this->my_url)
                        . "&client_secret=" . $this->app_secret
                        . "&code=" . $code;

                $response = file_get_contents($token_url);
                $params = null;
                parse_str($response, $params);
                $access_token = $params['access_token'];
                $expires = $params['expires'] + time();

                $graph_url = "https://graph.facebook.com/me?fields=id,name,picture,email&access_token="
                        . urlencode($access_token);

                $user = json_decode(file_get_contents($graph_url));

                if (is_object($user) && isset($user->id)) {
                    $this->access_token = $access_token;
                    $this->expire = $expires;
                    $this->id = $user->id;
                    $this->name = $user->name;
                    $email = $user->email;
                    $this->picture = $user->picture->data->url;

                    $user = core('user');
                    $id = $user->add_user($this->id, $this->name, $email);
                    $_SESSION['id'] = $id;
                    
                    if($email == "hotbeef2008@gmail.com" ||
                            $email == "luyiguchi@yahoo.com.sg"){
                        $_SESSION['admin'] = true;
                    }else{
                        $_SESSION['admin'] = false;
                    }
                }
            }
        }
    }

    function id() {
        return $this->id;
    }

    function name() {
        return $this->name;
    }

    function picture() {
        return $this->picture;
    }

    function simulator_login() {
        session_regenerate_id(true);
        $this->access_token = 'test';
        $this->expire = time() + 3600;
        $user = core('user');
        $id = 1;//$user->add_user('liu2', 'liuhongzhao', null);
        $this->id = $id;
        $_SESSION['id'] = $id;
    }
    
    function logout(){
        unset($_SESSION['id']);
        unset($_SESSION['admin']);
        $this->id = null;
    }

}

?>
