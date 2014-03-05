<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config.php';

function define_menu() {
    $menu = array();
    $menu['button'] = array();

    $item1 = array();
    $item1['type'] = 'click';
    $item1['name'] = '我要订餐';  // mb_convert_encoding( '开始订餐' , 'GBK', 'utf-8');
    $item1['key'] = 'START_ORDER';

    $item2 = array();
    $item2['type'] = 'click';
    $item2['name'] = '看我订单'; // mb_convert_encoding( '查看订单' , 'GBK', 'utf-8');
    $item2['key'] = 'VIEW_ORDER';

    array_push($menu['button'], $item1);
    array_push($menu['button'], $item2);

    $menu_json_str = t2s_json_encode($menu);
    print $menu_json_str;
    //exit();

    $access_token = get_accesstoken();
    //$params['access_token'] = $access_token;

    $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
    echo $url."\n{$params['body']}\n";
    print_r( geturl($url, 'post', null, $menu_json_str) );
    echo "1\n";
}

function t2s_json_encode($array) {
    $str = "";
    if (is_assoc($array)) {
        foreach ($array as $k => $v) {
            if (is_numeric($v))
                $str .= ",\"$k\":$v";
            else if (is_string($v))
                $str .= ",\"$k\":\"$v\"";
            else
                $str .= ",\"$k\":" . t2s_json_encode($v);
        }
        return '{' . substr($str, 1) . '}';
    } else {
        foreach ($array as $v) {
            if (is_numeric($v))
                $str .= ",$v";
            else if (is_string($v))
                $str .= ",\"$v\"";
            else
                $str .= ',' . t2s_json_encode($v);
        }
        return '[' . substr($str, 1) . ']';
    }
}

function is_assoc($arr) {
    return array_keys($arr) !== range(0, count($arr) - 1);
}

define_menu();