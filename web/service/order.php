<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once MAIN_PATH.'/address.php';

class start_order extends menu{

    function start_order(){
        parent::menu();
    }
    
    function replace_menu_str($meals){
        $menu = '';
        $index = 1;
        foreach($meals as $meal){
            $menu .= "【{$index}】" . $meal['name'] . $meal['price'] . "元 \n";
            $this->list_command[$index] = new order_meal($meal);
            $index++;
        }
        
        return $menu;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
        
        $this->next_action = true;
        
        $msisdnProcess = new msisdnProcess();
        $meals = $msisdnProcess->get_meals();
        
        $menu = $this->replace_menu_str($meals);
        $menu_str = preg_replace('/%s/', $menu, $menu_str, 1);

        $command = new command();
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        $object = null;
        if($input == 'M'){
            $object = new main_menu();
        }else if($input == 'Q'){
            $object = new order_infos();
        }else{
            return $this->int_action($input, $fb_id, $msisdn, $shortcode);
        }
        
//        return $this->base_action($input, $fb_id, $msisdn, $shortcode, $object);
        $object->showOwnerList($fb_id, $msisdn, $shortcode, $input);
    }
}

class order_infos extends menu{
    function order_infos(){
        parent::menu();
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        //$menu_str = $this->init_menu_str();
        $menu_str = array();
        
        $msisdnProcess = new msisdnProcess();
        $meals = $msisdnProcess->get_meals();
        
        array_push($menu_str,array('Title'=>'好佳订餐表','Description'=>"",
                    'PicUrl'=>'http://115.29.15.140/service/head.jpg','Url'=>"http://115.29.15.140?msisdn={$msisdn}&index=0"));
        
        $index = 1;
        foreach($meals as $meal){
            $menu = array('Title'=>$meal['name'],'Description'=>"价格{$meal['price']}元",
                    'PicUrl'=>'','Url'=>"http://115.29.15.140?msisdn={$msisdn}&index=$index");
                    
            $index++;
            
            if($index == 10){
                break;
            }
                    
            array_push($menu_str, $menu);
        }
        
        $this->next_action = true;
        
        $command = new command();
        $command->returnMessageAndNotSaveAction($msisdn, $shortcode, $menu_str, $this, 'mutiple');
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        $main_menu = new main_menu();
        return $main_menu->showOwnerList($fb_id, $msisdn, $shortcode, $message);
    }
}

class order_meal extends menu{
    var $meal;
    function order_meal($meal){
        parent::menu();
        $this->meal = $meal;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu = $this->init_menu_str();
        $str = "{$this->meal['name']} {$this->meal['price']}元";
        
        $menu = preg_replace('/%s/', $str, $menu, 1);
        
        $command = new command();
        $this->next_action = true;
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        if(is_numeric($input)){
            $order = array($this->meal['name']=>array('count'=>$input,'price'=>$this->meal['price']));
            
            $order_detail = new continue_order($order);
//            $this->base_action($input, $fb_id, $msisdn, $shortcode, $order_detail);
            $order_detail->showOwnerList($fb_id, $msisdn, $shortcode, $input);
            return true;
        }
        $this->next_action = false;
        return $this->showOwnerList($fb_id, $msisdn, $shortcode, $input);
    }
}

class continue_order extends menu{
    var $order;
    function continue_order($order){
        parent::menu();
        
        $this->order = $order;
    }
    
    function replace_menu_str($meals){
        $menu = '';
        $index = 1;
        foreach($meals as $meal){
            $menu .= "【{$index}】" . $meal['name'] . $meal['price'] . "元 \n";
            $this->list_command[$index] = new continue_order_meal($this->order, $meal);
            $index++;
        }
        
        return $menu;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
        $total = 0;
        $menu = '';
        foreach ($this->order as $name=>$desc){      
            $menu .= $name.$desc['price']."元\n";
            
            $all_price = $desc['price'] * $desc['count'];
            $menu .= "您订了 {$desc['count']} 份 共 {$all_price} 元\n";
            $total += $all_price;
        }
        
        $menu .= "总共{$total}元\n";
        
        $menu_str = preg_replace('/%s/', $menu, $menu_str, 1);
        
        $command = new command();
        
        $this->next_action = true;
        
        $msisdnProcess = new msisdnProcess();
        $meals = $msisdnProcess->get_meals();
        
        $menu2 = $this->replace_menu_str($meals);
        $menu_str = preg_replace('/%s/', $menu2, $menu_str, 1);
        
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        if(strtoupper($input) == 'A'){
            $order_confirm = new order_confrim($this->order);
            
            return $order_confirm->showOwnerList($fb_id, $msisdn, $shortcode, $input);
        }
        
        return $this->int_action($input, $fb_id, $msisdn, $shortcode);
    }
}

class order_confrim extends menu{
    var $order;
    function order_confrim($order){
        parent::menu();
        $this->order = $order;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $msisdnProcess = new msisdnProcess();
        $addresses = $msisdnProcess->get_address($msisdn);
        
        $object = null;
        if($addresses && count($addresses) > 0){
            $object = new user_have_addresses($this->order, $addresses);
        }else{
            $object = new user_add_a_addresses($this->order);
        }
        
        $object->showOwnerList($fb_id, $msisdn, $shortcode, $message);
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        $this->order['address'] = $input;
        
        $create_order = new create_order($this->order);
        return $create_order->showOwnerList($fb_id, $msisdn, $shortcode, $input);
    }
}

class create_order extends menu{
    var $order;
    function create_order($order){
        parent::menu();
        $this->order = $order;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $msisdnProcess =new msisdnProcess();
        $this->order['date'] = date('Y-m-d H:i:s');
        $msisdnProcess->set_cur_order('', $msisdn, $this->order);
        
        $menu_str = $this->init_menu_str();
        
        $menu = '';
        $index=1;
        $all = 0;
        
        foreach($this->order as $item=>$desc){
            if($item == 'address' || $item == 'date' || $item == 'id'){
                continue;
            }
            $total = $desc['count'] * $desc['price'];
            $menu .= "{$item} 您订了 {$desc['count']} 份 ，共 {$total} 元\n";
            
            $all += $total;
        }
        
        $menu .= "合计 {$all} 元\n";
        $menu .= "订餐地址信息：{$this->order['address']}\n";
        
        $menu_str = preg_replace('/%s/', $menu, $menu_str, 1);
        
        $command = new command();
        $this->next_action = true;
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        if(strtoupper($input) == 'V'){
            $view = new view_orders();
            $view->showOwnerList($fb_id, $msisdn, $shortcode, $input);
            return true;
        }
        
        $main_menu = new main_menu();
        return $main_menu->showOwnerList($fb_id, $msisdn, $shortcode, $input);
    }
}

class view_orders extends menu{
    function view_orders(){
        parent::menu();
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $msisdnProcess = new msisdnProcess();
        $orders = $msisdnProcess->get_cur_order($msisdn);
        
        $menu_str = $this->init_menu_str();
        $menu = '';
        $index = 1;
        foreach ($orders as $order){
            $menu .= "【{$index}】";
            $menu .= $order['date']."\n";
            $this->list_command[$index] = new order_detail($order);
            $index ++;
        }
        
        $menu_str = preg_replace('/%s/', $menu, $menu_str, 1);
        
        $command = new command();
        
        $this->next_action = true;
        
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        if(strtoupper($input) == 'M'){
            $main_menu = new main_menu();
            return $main_menu->showOwnerList($fb_id, $msisdn, $shortcode, $input);
        }
        
        return $this->int_action($input, $fb_id, $msisdn, $shortcode);
    }
}

class order_detail extends menu{
    var $order;
    function order_detail($order){
        parent::menu();
        $this->order = $order;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
        
        $menu = '';
        $total = 0;
        foreach($this->order as $item=>$desc){
            if($item == 'address' || $item == 'date' || $item == 'id'){
                continue;
            }
            
            $menu .= "$item\n";
            
            $all_price = $desc['price'] * $desc['count'];
            $menu .= "您订了 {$desc['count']} 份 共 {$all_price} 元\n";
            $total += $all_price;
        }
        
        $menu .= "总共 $total 元\n";
        $menu .= "地址信息：{$this->order['address']}\n";
        
        $menu_str = preg_replace('/%s/', $menu, $menu_str, 1);
        
        $command = new command();
        $this->next_action = true;
        
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        if(strtoupper($input) == 'D'){
            $delete = new delete_order($this->order['id']);
            $delete->showOwnerList($fb_id, $msisdn, $shortcode, $input);
            
            return true;
        }
        
        $main_menu = new main_menu();
        return $main_menu->showOwnerList($fb_id, $msisdn, $shortcode, $input);
    }
}

class delete_order extends menu{
    var $id;
    function delete_order($id){
        parent::menu();
        
        $this->id = $id;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $msisdnProcess = new msisdnProcess();
        
        $msisdnProcess->delete_order($this->id);
        
        $view_orders = new view_orders();
        
        return $view_orders->showOwnerList($fb_id, $msisdn, $shortcode, $message);
    }
}

class continue_order_meal extends menu{
    var $order;
    var $meal;
    function continue_order_meal($order, $meal){
        parent::menu();
        $this->order = $order;
        $this->meal = $meal;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu = $this->init_menu_str();
        $str = "{$this->meal['name']}{$this->meal['price']}元";
        
        $menu = preg_replace('/%s/', $str, $menu, 1);
        
        $command = new command();
        $this->next_action = true;
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        if(is_numeric($input)){
            if(isset($this->order[$this->meal['name']]['count'])){
                $this->order[$this->meal['name']]['count'] += $input;
            }else{
                $this->order[$this->meal['name']] = array('count' => $input, 'price'=>$this->meal['price']);
            }
            
            $order_detail = new continue_order($this->order);
//            $this->base_action($input, $fb_id, $msisdn, $shortcode, $order_detail);
            $order_detail->showOwnerList($fb_id, $msisdn, $shortcode, $input);
            return true;
        }
        $this->next_action = false;
        return $this->showOwnerList($fb_id, $msisdn, $shortcode, $input);
    }
}