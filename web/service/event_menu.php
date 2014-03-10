<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once MAIN_PATH.'/menu.php';

class event_menu{
    function event_menu(){
//        parent::menu();
    }
    
    function action($input, $fb_id = "", $msisdn = "", $shortcode = ""){
        $object = null;
        if($input == 'START_ORDER'){
            $object = new order_infos();
        }else if($input == 'VIEW_ORDER'){
            $object = new event_view_orders();
        }
        
        if($object){
            return $object->showOwnerList($fb_id, $msisdn, $shortcode, $input);
        }
    }
}

class event_view_orders extends menu{
    function event_view_orders(){
        parent::menu();
    }
    
    function showOwnerList($fb_id, $msisdn = "", $shortcode = "", $message = "") {
        $msisdnProcess = new msisdnProcess();
        $orders = $msisdnProcess->get_cur_order($msisdn);
        $menu_str = array();
        
        if(count($orders) > 0){
            $all_total = 0;
            $detail = '';
            $order = $orders[0];
            foreach($order['order_info']['meal'] as $item=>$desc){
                $name = "$item";
                $count = "{$desc['count']}份";
                $price = $desc['price']*$desc['count'];
                $all_total += $price;
                $total = "{$price}元";

                $detail .= "$name 您订了 $count 单价为{$desc['price']}\n";
            }
            
            array_push($menu_str,array('Title'=>"查看您的订单记录",'Description'=>"{$order['date']} \n$detail \n订单总价为 {$all_total} 元",
                    'PicUrl'=>'','Url'=>"http://115.29.15.140/order/view/$msisdn") );
        }else{
            array_push($menu_str,array('Title'=>"查看您的订单记录",'Description'=>"订单总价为 0 元\n订单数量为 0 单\n",
                    'PicUrl'=>'','Url'=>"http://115.29.15.140/order/view/$msisdn") );
        }
        
        $command = new command();
        $command->returnMessageAndNotSaveAction($msisdn, $shortcode, $menu_str, $this, 'mutiple');
        
        return true;
    }
}