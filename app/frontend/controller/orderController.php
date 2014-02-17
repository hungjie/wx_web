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
}