<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$info = "";
$all_total = 0;
$user_id = $data['user_id'];
$order_id = $data['order_id'];
foreach ($data['order'] as $item => $desc) {
    if ($item == 'address' || $item == 'date' || $item == 'id') {
        continue;
    }
    
    $name = "<p class='text-left col-xs-6'>$item</p>";
    $count = "<p class='text-left col-xs-3'><strong>{$desc['count']}</strong>份</p>";
    $price = $desc['price']*$desc['count'];
    $all_total += $price;
    $total = "<p class='text-left col-xs-3'><strong>{$price}</strong>元</p>";
    
    $info .= "<div class='row'>$name $count $total</div>";
}

$addr_info = "<div class='row'><p class='text-left col-xs-12'>地址及联系信息：<strong>{$data['order']['address']}</strong></p></div>";

$total_price_html = "<div class='row'><p class='text-right col-xs-12'>合计<strong>$all_total</strong>元</p></div>";
?>

<div id="wrap">

    <!-- Begin page content -->
    <div class="container">
        <div class="page-header">
            <h1>订单提交成功</h1>
        </div>
        <?php echo $info;?>
        <hr>
        <?php echo $total_price_html;?>
        <?php echo $addr_info;?>
        
        <form id='form_id' class="form-horizontal" role="form" action="/index/cancel_order" method='post'>
            <input class='hidden' name='user_id' value='<?php echo $user_id; ?>'>
            <input class='hidden' name='order_id' value='<?php echo $order_id; ?>'>
            <div class="form-group">
                <button id="submit_modal" type='submit' class="btn btn-danger">取消订单</button>
            </div>
        </form>
    </div>
</div>