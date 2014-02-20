<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class systemCore{
    function is_out_date_or_count(){
        $db = get_db();
        $db->begin_query();
        $res = $db->table('system_config')->exec();
        
        if($res && $res->next()){
            $start_am = $res->__data['start_am'];
            $end_am = $res->__data['end_am'];
            $count = $res->__data['meal_count'];
            
            $date = date("Y-m-d");
            
            $start = $date." $start_am";
            $end = $date." $end_am";
            
            $now = date('Y-m-d H:i:s',time());
            if( $start > $now || $end < $now 
                    || $count <= 0){
                return array(false, strtotime($start), strtotime($end), $count);
            }
            
            return array(true, strtotime($start), strtotime($end), $count);
        }
        
        return false;
    }
}