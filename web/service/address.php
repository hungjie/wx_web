<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class manage_your_address extends menu{
    function manage_your_address(){
        parent::menu();
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
        $msisdnProcess = new msisdnProcess();
        $address = $msisdnProcess->get_address($msisdn);
        
        $index = 1;
        $menu = '';
        foreach ($address as $addr){
            $menu .= "【{$index}】 $addr \n";
            $index++;
        }
        
        $menu_str = preg_replace('/%s/', $menu, $menu_str, 1);
        
        $this->next_action = true;
        $command = new command();
        
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        if(strtoupper($input) == 'A'){
            $user_add_a_addresses = new user_add_a_addresses(false);
            return $user_add_a_addresses->showOwnerList($fb_id, $msisdn, $shortcode, $input);
        }
        
        $main_menu = new main_menu();
        return $main_menu->showOwnerList($fb_id, $msisdn, $shortcode, $input);
    }
}

class user_add_a_addresses extends menu{
    var $order;
    function user_add_a_addresses($order){
        parent::menu();
        $this->order = $order;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
        
        $command = new command();
        $this->next_action = true;
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
    
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        $user_name_address = new user_name_address($input, $this->order);
        
        return $user_name_address->showOwnerList($fb_id, $msisdn, $shortcode, $input);
    }
}

class user_name_address extends menu{
    var $address;
    var $order;
    function user_name_address($address, $order){
        $this->address = $address;
        $this->order = $order;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();

        $command = new command();
        $this->next_action = true;
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        $user_phone_address = new user_phone_address($this->address, $this->order, $input);
        return $user_phone_address->showOwnerList($fb_id, $msisdn, $shortcode, $input);
    }
}

class user_phone_address extends menu{
    var $address;
    var $order;
    var $name;
    
    function user_phone_address($address, $order, $name){
        parent::menu();
        $this->address = $address;
        $this->order = $order;
        $this->name = $name;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
        $command = new command();
        $this->next_action = true;
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        $addr = "{$this->address} , {$this->name} , {$input}";
        
        $add_address = new add_your_address($addr);
        
        if($this->order){
            $add_address->add_address($msisdn);
            $order_confrim = new order_confrim($this->order);
            return $order_confrim->action($addr, $fb_id, $msisdn, $shortcode);
        }
        
        return $add_address->showOwnerList($fb_id, $msisdn, $shortcode, $input);
    }
}

class add_your_address extends menu{
    var $address;
    function add_your_address($address){
        parent::menu();
        
        $this->address = $address;
    }
    
    function add_address($msisdn){
        $msisdnProcess = new msisdnProcess();
        $addresses = $msisdnProcess->get_address($msisdn);
        
        if($addresses){
            if(count($addresses) >=3 ){
                array_shift($addresses);
            }
            
            if(!is_array($addresses)){
                $addresses = array();
            }
            
            $type = 1;
        }else{
            $addresses = array();
            $type = 2;
        }
        
        array_push($addresses, $this->address);
        
        $msisdnProcess->set_address($msisdn, $addresses, $type);
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $this->add_address($msisdn);
        
        $manage_your_address = new manage_your_address();
        $manage_your_address->showOwnerList($fb_id, $msisdn, $shortcode, $message);
        
        return true;
    }
}

class user_have_addresses extends menu{
    var $order;
    var $addresses;
    function user_have_addresses($order, $addresses){
        parent::menu();
        $this->order = $order;
        $this->addresses = $addresses;
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $menu_str = $this->init_menu_str();
        $menu = '';
        $index = 1;
        foreach($this->addresses as $address){
            $menu .= "【{$index}】 {$address}\n";
            $this->order['address'] = $address;
            
            $this->list_command[$index] = new create_order($this->order);
            $index++;
        }
        
        $menu_str = preg_replace('/%s/', $menu, $menu_str, 1);
        
        $this->next_action = true;
        
        $command = new command();
        $command->returnMessageAndSaveAction($msisdn, $shortcode, $menu_str, $this);
        return true;
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = "") {
        if(strtoupper($input) == 'A'){
            $user_add_a_addresses = new user_add_a_addresses($this->order);
            return $user_add_a_addresses->showOwnerList($fb_id, $msisdn, $shortcode, $input);
        }
        
        return $this->int_action($input, $fb_id, $msisdn, $shortcode);
    }
}
 