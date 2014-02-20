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

    function set_cur_order($id, $msisdn, $order, $now) {
        $db = get_db();
        $db->begin_query();
        $db->__table = 'user_order';
        $encode = json_encode($order);

        if ($id) {
            $db->update(array('order_info' => $encode), array('id' => $id));

            return $id;
        }

        $order_id = $db->insert(array('user_id' => $msisdn, 'order_info' => $encode, 'time_at' => $now));

        $db->__table = 'user_order_detail';
        foreach ($order as $k => $v) {
            if ($k == 'date' || $k == 'address' || !is_array($v))
                continue;
            $db->insert(array('user_order_id' => $order_id, 'meal_name' => $k, 'count' => $v['count'], 'time_at' => $now));
        }

        return $order_id;
    }

    function get_cur_order($msisdn) {
        $db = get_db();
        $db->begin_query();
        $today_date = date("Y-m-d");
        $res = $db->table('user_order')->where(array('user_id' => $msisdn, 'status' => 0))
                ->where("time_at > '$today_date'")
                ->order_by_desc('id')
                ->exec();

        $reses = array();
        while ($res && $res->next()) {
            $order_info = json_decode($res->__data['order_info'], true);
            $order_info['id'] = $res->__data['id'];
            array_push($reses, $order_info);
        }

        return $reses;
    }

    function get_meal_detail($begin, $end) {
        $db = get_db();
        $db->begin_query();
        $res = $db->table('user_order_detail')->where(array("time_at >='$begin' and time_at <= '$end'"))
                ->select('date_format(time_at, "%Y-%m-%d%p") date_am_pm, meal_name, sum(count) sum')
                ->order_by_desc('date_am_pm')
                ->group_by('date_am_pm , meal_name')
                ->exec();
        $return = array();
        while ($res->next_assoc()) {
            $return[] = $res->__data;
        }
        return $return;
    }

    function get_order_detail($begin, $end) {
        $db = get_db();
        $db->begin_query();
        $res = $db->table('user_order')->where(array("time_at >='$begin' and time_at <= '$end'"))
                ->exec();
        $return = array();
        while ($res->next_assoc()) {
            $order_item = $res->__data;
            $order_item['order_info'] = json_decode($order_item['order_info'], true);
            $order_item['address'] = $order_item['order_info']['address'];
            $return[] = $order_item;
        }
        usort($return, 'order_by_addr_func');
        return $return;
    }

    function delete_order($user_id, $order_id) {
        $db = get_db();
        $db->begin_query();
        $db->__table = 'user_order';

        $db->delete(array('user_id' => $user_id, 'id' => $order_id));
        $db->__table = 'user_order_detail';
        $db->delete(array('user_order_id' => $order_id));
        return true;
    }

}

function order_by_addr_func($a, $b) {
    $r = strcmp($a['address'], $b['address']);
    if ($r == 0) $r = strcmp($a['time_at'], $b['time_at']);
//    print "<br/>being<br/>";
//    print $a['address'] . " " . $a['time_at'];
//    print "<br/>";
//     print $b['address'] . " " . $b['time_at'];
//    print "<br/>";
//     print $r;
//    print "<br/>end<br/>";
    return $r;
}

