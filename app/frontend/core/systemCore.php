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

    function set_var($k, $v){
        $db = get_db();
        $k = mysql_escape_string($k);
        $v = mysql_escape_string(json_encode($v));
        $db->__query("insert into system_var values('$k','$v') on DUPLICATE KEY UPDATE name='$v'", 'I');
    }
    function get_var($k){
         $db = get_db();
         $r = $db->begin_query()->table('system_var')->where(array('name'=> $k))->exec();
         if ($r->next())
             return json_decode ($r->value, true);
         else return null;
    }
}