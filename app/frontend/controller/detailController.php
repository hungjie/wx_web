<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class detailController {

    function detailController() {
        
    }

    function meal() {
        $mealcore = core('meal');
        $res = $mealcore->get_meal_detail(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));
        print_r($res);
        return false;
    }

    function order() {
        $mealcore = core('meal');
        $res = $mealcore->get_order_detail(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));
        foreach($res as $v){
            print_r($v);
            print "<hr><br/>";
        }
        return false;
    }

}

?>