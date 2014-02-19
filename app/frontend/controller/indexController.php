<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class indexController{
    function indexController(){
//        if (!isset($_SESSION['id']) && !isset($_SESSION['logout'])) redirection ('/fblogin');        
//        if (!isset($_SESSION['id']) && @strstr($_SERVER['HTTP_REFERER'], 'facebook.com')) redirection ('/fblogin');        
    }
    
    function index(){
        $msisdn = $_GET['msisdn'];
        
        $mealCore = core('meal');
        $meals = $mealCore->getmeals();
        
        $addrCore = core('addr');
        $addrs = $addrCore->get_address($msisdn);
        
        content(array('user_id'=>$msisdn,
            'meals'=>$meals, 'addrs'=>$addrs), 'all_meals');
        layout('layout');
    }
    
    function test(){
        $db = get_db();
        $res = $db->begin_query()->table('user_order')->where('1')->exec();
        while($res->next()){
            print_r(json_decode($res->order_info));
            print_r(($res->order_info));
        }
        return false;
    }
    
    function order_confirm(){
        $user_id = $_POST['user_id'];
        $index = $_POST['index'];
        
        if(empty($user_id) || empty($index) || $index == 0){
            return;
        }
        
        $now = date('Y-m-d H;i:s');
        $order = array();
        $i = 1;
        for(;$i < $index; $i++){
            $name = $_POST["name$i"];
            $price = $_POST["price$i"];
            $count = $_POST["count$i"];
            
            if(!is_numeric($count) || $count <= 0){
                continue;
            }
            
            $order[$name]=array('count'=>$count,'price'=>$price);
        }
        
        $inputphone = $_POST['inputphone'];
        $inputname = $_POST['inputname'];
        $inputaddress = $_POST['inputaddress'];
        
        $order['address'] = "$inputaddress,$inputname,$inputphone";
        $order['date'] = $now;
        
        $addrCore = core('addr');
        $addresses = $addrCore->get_address($user_id);
        $already_exsit_addr = false;
        foreach($addresses as $address){
            if($address == $order['address']){
                $already_exsit_addr = true;
            }
        }
        
        if(!$already_exsit_addr){
            if(count($addresses) >= 3){
                array_shift($addresses);
            }
            
            if($addresses == false){
                $addresses = array();
            }
            
            array_push($addresses, $order['address']);
        }
        
        $addrCore->set_address($user_id, $addresses, count($addresses));
        
        $mealCore = core('meal');
        $order_id = $mealCore->set_cur_order('', $user_id, $order, $now);
        
        content(array('user_id'=>$user_id,'order'=>$order, 'order_id'=>$order_id), 'order_success');
        layout('layout');
    }
    
    function cancel_order(){
        $user_id = $_POST['user_id'];
        $order_id = $_POST['order_id'];
        
        if( empty($user_id) || empty($order_id) ){
            return;
        }
        
        $mealCore = core('meal');
        $mealCore->delete_order($user_id, $order_id);
        
        content(array('user_id'=>$user_id), 'cancel_order_success');
        layout('layout');
    }
}
?>
