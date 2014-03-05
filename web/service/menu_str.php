<?php


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$_menu_str_ = array(
    "main_menu"=>array(
        'menu'=>"你好，欢迎关注开心订餐，很抱歉，服务还未开启，请耐心等候。
 回复【1】 开始订餐
 回复【2】 查看订餐信息
 
回复1-2",
        'menu_back'=>"你好，欢迎关注开心订餐
 回复【1】 开始订餐
 回复【2】 查看快餐信息
 回复【3】 查看您的订单
 回复【4】 编辑您的地址
 
回复1-4",
    ),
    "start_order"=>array(
        'menu'=>"您好，以下是当天的订餐信息\n"
        . "%s\n"
        . "回复数字编号订餐\n"
        . "回复M返回主页面\n"
        . "回复Q查看快餐信息\n",
    ),
    "order_infos"=>array(
        'menu'=>"订单信息，以后考虑换图片
这应该是一张图片~~~
",
    ),
    
    "continue_order"=>array(
        'menu'=>"您当前的订单内容：\n %s \n"
        . "%s"
        . "回复数字编号继续订餐\n"
        . "回复A完成订单\n"
    ),
    
    "order_meal"=>array(
        'menu'=>"您想订多少份%s\n
请回复份数，如，订3份，回复3.",
    ),
    
    "continue_order_meal"=>array(
        'menu'=>"您想订多少份%s\n
请回复份数，如，订3份，回复3.",
    ),
    
    "order_confrim"=>array(
        'menu'=>"请输入您的地址? \n"
        . "格式以 （地址，姓名，联系电话）， 如：马垄路17号C605，小东东，12345678901",
    ),
    
    "create_order"=>array(
        'menu'=>"您已经成功生成订单！订单信息如下：\n"
        . "%s\n"
        . "回复V查看订单\n"
        . "回复任意字母回主菜单\n"
    ),
    
    "view_orders"=>array(
        'menu'=>"以下是您的订单：\n"
        . "%s\n"
        . "回复M回主菜单\n",
    ),
    
    "order_detail"=>array(
        'menu'=>"%s\n"
        . "回复D删除订单\n"
        . "回复M返回主菜单\n",
    ),
    
    "user_add_a_addresses"=>array(
        'menu'=>"请耐心填写您的地址，现在送餐只接受厦门市软件园二期的订单，请回复您是在哪栋楼几零几？",
    ),
    
    "user_name_address"=>array(
        'menu'=>"请输入您的姓名？"
    ),
    
    "user_phone_address"=>array(
        'menu'=>"请输入您的联系电话？",
    ),
    
    "manage_your_address"=>array(
        'menu'=>"下面是您配置的地址信息，最多只能有3条，超过则替代旧地址。\n"
        . "%s\n"
        . "回复A添加新地址\n"
    ),
    
    "user_have_addresses"=>array(
        'menu'=>"请选择您以编辑过的地址:\n"
        . "%s\n"
        . "请选择您要的地址\n"
        . "回复A编辑新地址\n"
    )
);

?>
