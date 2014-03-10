<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class indexController {

    function indexController() {
//        if (!isset($_SESSION['id']) && !isset($_SESSION['logout'])) redirection ('/fblogin');        
//        if (!isset($_SESSION['id']) && @strstr($_SERVER['HTTP_REFERER'], 'facebook.com')) redirection ('/fblogin');        
    }

    function index() {
        $msisdn = $_GET['msisdn'];
        $index = $_GET['index'];

        if (empty($msisdn)) {
            return;
        }

        $_SESSION['user_id'] = $msisdn;

        $system = core('system');
        $system_config = $system->config();

        if (!$system_config) {
            return;
        }

        $shop_status = $system->shop_status($system_config, 'hungjie');

        $order_Ymd = date('YmdA');
        $ordered_meal_count = $system->get_var($order_Ymd);

        $left_meal = $system_config['meal_count'] - $ordered_meal_count;

        if (strncmp($shop_status, 'close', 5) == 0) {
            content(array('start_am' => $system_config['start_am'],
                'end_am' => $system_config['end_am'],
                'start_pm' => $system_config['start_pm'],
                'end_pm' => $system_config['end_pm'],
                'count' => $left_meal), 'stop_order');
            return;
        }

        $mealCore = core('meal');
        $meals = $mealCore->getmeals();
        $_SESSION['meals'] = $meals;

        $addrCore = core('addr');
        $addrs = $addrCore->get_address($msisdn);

        content(array('user_id' => $msisdn,
            'meals' => $meals,
            'addrs' => $addrs,
            'start_am' => $system_config['start_am'],
            'end_am' => $system_config['end_am'],
            'start_pm' => $system_config['start_pm'],
            'end_pm' => $system_config['end_pm'],
            'count' => $left_meal,
            'index' => $index), 'all_meals');
    }

    function test() {
        $db = get_db();
        $res = $db->begin_query()->table('user_order')->where('1')->exec();
        while ($res->next()) {
            print_r(json_decode($res->order_info));
            print_r(($res->order_info));
        }
        return false;
    }

    function order_confirm() {
        $user_id = $_POST['user_id'];
        $index = $_POST['index'];

        if ($user_id != $_SESSION['user_id'] || empty($user_id) || empty($index) || $index == 0) {
            return;
        }

        $system = core('system');
        $system_config = $system->config();

        if (!$system_config) {
            return;
        }

        $shop_status = $system->shop_status($system_config, 'hungjie');

        if (strncmp($shop_status, 'close', 5) == 0) {
            content(array('start_am' => $system_config['start_am'],
                'end_am' => $system_config['end_am'],
                'start_pm' => $system_config['start_pm'],
                'end_pm' => $system_config['end_pm'],
                'count' => $left_meal), 'stop_order');
            return;
        }

        $now = date('Y-m-d H:i:s');
        $order = array();
        $total_price = 0;
        $total_count = 0;
        foreach ($_POST['o'] as $k => $v) {
            $count = $v;
            if (!is_numeric($count) || $count <= 0) {
                continue;
            }
            $name = $_SESSION['meals'][$k]['name'];
            $price = $_SESSION['meals'][$k]['price'];
            $order['meal'][$name] = array('count' => $count, 'price' => $price);
            $total_count += $count;
            $total_price += $count * $price;
        }

        $order['total_count'] = $total_count;
        $total_price -= (int)($total_count / 10) * 10; 
        $order['total_price'] = $total_price;

        $order_Ymd = date('YmdA');
        $ordered_meal_count = $system->get_var($order_Ymd);
        $system->set_var($order_Ymd, $ordered_meal_count + $total_count);

        $inputarea = str_replace(',', ' ', $_POST['inputarea']);
        $inputphone = str_replace(',', ' ', $_POST['inputphone']);
        $inputname = str_replace(',', ' ', $_POST['inputname']);
        $inputaddress = str_replace(',', ' ', $_POST['inputaddress']);

        $order['address'] = "$inputarea,$inputaddress,$inputname,$inputphone";
        $order['date'] = $now;

        $addrCore = core('addr');
        $addresses = $addrCore->get_address($user_id);
        $already_exsit_addr = false;
        foreach ($addresses as $k => $address) {
            if ($address == $order['address']) {
                $already_exsit_addr = true;
                if ($k != 0) { // not first/default addr
                    unset($addresses[$k]);
                    array_unshift($addresses, $address);
                    $addrCore->set_address($user_id, $addresses, 1);
                }
                break;
            }
        }

        if (!$already_exsit_addr) {
            if (count($addresses) >= 3) {
                array_pop($addresses);
            }

            if ($addresses == false) {
                $addresses = array();
            }

            $type = count($addresses);

            array_unshift($addresses, $order['address']);
            $addrCore->set_address($user_id, $addresses, $type);
        }

        $mealCore = core('meal');
        $order_id = $mealCore->set_cur_order('', $user_id, $order, $now);

        print generate_html(array('user_id' => $user_id, 'order' => $order, 'order_id' => $order_id), 'order_success');
        return false;

//        content(array('user_id'=>$user_id,'order'=>$order, 'order_id'=>$order_id), 'order_success');
//        layout('layout');
    }

    function cancel_order() {
        $user_id = $_POST['user_id'];
        $order_id = $_POST['order_id'];

        if (empty($user_id) || empty($order_id)) {
            return;
        }

        $mealCore = core('meal');
        $mealCore->delete_order($user_id, $order_id);

        print generate_html(array('user_id' => $user_id), 'cancel_order_success');
        return false;
//        content(array('user_id'=>$user_id), 'cancel_order_success');
//        layout('layout');
    }

}

?>
