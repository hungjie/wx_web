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
        foreach($res as $v){
             print_r($v);
            print "<hr><br/>";
        }
        return false;
    }

    function order() {
        $mealcore = core('meal');
        $res = $mealcore->get_order_detail(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));
        foreach($res as $v){
            print $v['address'] . '<br/>' . $v['time_at'];
            $price = 0;
            foreach ($v['order_info']['meal'] as $kk => $vv){
                if (!is_array($vv)) continue;
                print "<br/>";
                print "$kk {$vv['count']} * {$vv['price']}";
                $price += $vv['count'] * $vv['price'];
            }
            print "<br/>total:$price<br/>";
            print "<hr>";
        }
        return false;
    }

}

?>