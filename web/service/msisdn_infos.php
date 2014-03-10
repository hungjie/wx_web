<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//status=0 never checkout.

class msisdnProcess {

    function get_cur_order($msisdn) {
        $db = get_db();
        $db->begin_query();
        $today_date = date("Y-m-d");
        $res = $db->table('user_order')->where(array('user_id' => $msisdn, 'status > 0'))
                ->where("time_at > '$today_date'")
                ->order_by_desc('id')
                ->exec();

        $reses = array();
        while ($res && $res->next()) {
            $order_info = $res->__data;
            $order_info['order_info'] = json_decode($res->__data['order_info'], true);
            array_push($reses, $order_info);
        }

        return $reses;
    }

    function set_cur_order($id, $msisdn, $order) {
        $db = get_db();
        $db->begin_query();
        $db->__table = 'user_order';
        $encode = json_encode($order);

        if ($id) {
            $db->update(array('order_info' => $encode), array('id' => $id));

            return $id;
        }

        $db->insert(array('user_id' => $msisdn, 'order_info' => $encode));
    }

    function delete_order($id) {
        $db = get_db();
        $db->begin_query();
        $db->__table = 'user_order';
        $db->delete(array('id' => $id));
    }

    function get_address($msisdn) {
        $db = get_db();
        $db->begin_query();

        $res = $db->table('addresses')->where(array('user_id' => $msisdn))->exec();

        if ($res && $res->next()) {
            return json_decode($res->__data['address'], true);
        }

        return false;
    }

    function set_address($msisdn, $address, $type) {
        $db = get_db();
        $db->begin_query();

        $addr_encode = json_encode($address);

        $db->__table = 'addresses';
        if ($type == 1) {
            $db->update(array('user_id' => $msisdn), array('address' => $addr_encode));
        } else {
            $db->insert(array('user_id' => $msisdn, 'address' => $addr_encode));
        }
    }

    function get_meals() {
        $db = get_db();
        $db->begin_query();

        $day_num = date('w');

        $res = $db->table('meal_list')->where("status = 'all' or status='$day_num'")
                        ->order_by('id')->exec();
        $reses = array();
        while ($res && $res->next()) {
            $reses[$res->id] = $res->__data;
        }

        return $reses;
    }

    function insert_meal($data) {
        $db = get_db();
        $db->begin_query();
        $db->__table = 'meal_list';

        $db->insert($data);
    }

    function add_user($id) {
        $db = get_db();
        $db->begin_query();
        $db->__table = 'user';

        $res = $db->table('user')->where(array('user_id' => $id))->exec();

        try {
            if ($res && $res->next()) {
                $db->update(array('user_id' => $id), array('status' => 1));
            } else {
                $db->insert(array('user_id' => $id));
            }
        } catch (Exception $e) {
            __error_log($e->getMessage(), __FILE__, __LINE__);
        }
    }

    function set_user_status($id, $status) {
        $db = get_db();
        $db->begin_query();
        $db->__table = 'user';

        $db->update(array('user_id' => $id), array('status' => $status));
    }

}

?>
