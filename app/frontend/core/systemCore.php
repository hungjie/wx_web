<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class systemCore {

    function config() {
        $db = get_db();
        $db->begin_query();
        $r = $db->table('system_config')->exec();

        $res = array();
        while ($r->next()) {
            $res[$r->name] = $r->value;
        }
        return $res;
    }
    
    function shop_status($system_config, $shop = 'hungjie'){        
        $hour = date('H:i');
        if ($hour < $system_config['start_am'])
            $shop_status = 'close_am';
        else if ($hour >= $system_config['start_am'] && $hour <= $system_config['end_am'])
            $shop_status = 'open_am';
        else if ($hour > $system_config['end_am'] && $hour < $system_config['start_pm'])
            $shop_status = 'close_ap';
        else if ($hour >= $system_config['start_pm'] && $hour <= $system_config['end_pm'])
            $shop_status = 'open_pm';
        else
            $shop_status = 'close_pm';
        return $shop_status;
    }

    function set_var($k, $v){
        $db = get_db();
        $k = mysql_escape_string($k);
        $v = mysql_escape_string(json_encode($v));
        $db->__query("insert into system_var values('$k','$v') on DUPLICATE KEY UPDATE value='$v'", 'I');
    }
    function get_var($k){
         $db = get_db();
         $r = $db->begin_query()->table('system_var')->where(array('name'=> $k))->exec();
         if ($r->next())
             return json_decode ($r->value, true);
         else return null;
    }
}