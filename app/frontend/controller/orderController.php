<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class orderController{
    function orderController(){
        
    }
    
    function view($msisdn){
        $meals = core('meal');
        $orders = $meals->get_cur_order($msisdn);
        
        content(array('user_id'=>$msisdn,'orders'=>$orders), 'order_views');
    }
    
    function cancel_order($user_id, $id){
        if( empty($user_id) || empty($id) ){
            return;
        }
        
        $mealCore = core('meal');
        $mealCore->delete_order_by_id($user_id, $id);
        
        $this->view($user_id);
    }
}