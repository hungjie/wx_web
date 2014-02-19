<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class addrCore{
    function get_address($msisdn){
        $db = get_db();
        $db->begin_query();
        
        $res = $db->table('addresses')->where(array('user_id'=>$msisdn))->exec();
        
        if($res && $res->next()){
            return json_decode($res->__data['address'], true);
        }
        
        return false;
    }
    
    function set_address($msisdn, $address, $count){
        $db = get_db();
        $db->begin_query();
        
        $addr_encode = json_encode($address);
        
        $db->__table = 'addresses';
        if($count == 0){
            $db->insert(array('user_id'=>$msisdn,'address'=>$addr_encode));
        }else{
            $db->update(array('user_id'=>$msisdn), array('address'=>$addr_encode));
        }
    }
}