<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class mealCore {

    function getmeals() {
        $db = get_db();
        $db->begin_query();

        $res = $db->table('meal_list')->exec();
        $reses = array();
        while ($res && $res->next()) {
            array_push($reses, $res->__data);
        }

        return $reses;
    }

    function set_cur_order($id, $msisdn, $order, $now){
        $db = get_db();
        $db->begin_query();
        $db->__table = 'user_order';
        $encode = json_encode($order);
        
        if($id){
            $db->update(array('order_info'=>$encode), array('id'=>$id));
            
            return $id;
        }
        
        $db->insert(array('user_id'=>$msisdn,'order_info'=>$encode,'time_at'=>$now));
    }
    
    function get_cur_order($msisdn){
        $db = get_db();
        $db->begin_query();
        $res = $db->table('user_order')->where(array('user_id'=>$msisdn, 'status'=>0))
                ->order_by_desc('id')
                ->exec();
        
        $reses = array();
        while($res && $res->next()){
            $order_info = json_decode($res->__data['order_info'], true);
            $order_info['id'] = $res->__data['id'];
            array_push($reses, $order_info);
        }
        
        return $reses;
    }
    
    function delete_order($user_id, $time_at){
        $db = get_db();
        $db->begin_query();
        $db->__table = 'user_order';
        
        return $db->delete(array('user_id'=>$user_id, 'time_at'=>$time_at));
    }
    
    function delete_order_by_id($user_id, $id){
        $db = get_db();
        $db->begin_query();
        $db->__table = 'user_order';
        
        return $db->delete(array('user_id'=>$user_id, 'id'=>$id));
    }
}
