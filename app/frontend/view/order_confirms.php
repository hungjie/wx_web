<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$infos = '';

if ($data['orders']) {
    foreach ($data['orders'] as $order) {
        $info = '';
        foreach ($order['order_info']['meal'] as $item => $desc) {
//            if ($item == 'address' || $item == 'date' || $item == 'id') {
//                continue;
//            }

            $name = "<p class='text-left col-xs-6'>$item</p>";
            $count = "<p class='text-left col-xs-3'><strong>{$desc['count']}</strong>份</p>";
            $price = $desc['price'] * $desc['count'];
            //$all_total += $price;
            $total = "<p class='text-left col-xs-3'><strong>{$price}</strong>元</p>";

            $info .= "<div class='row'>$name $count $total</div><hr>";
        }

        if ($order['status'] == 1) {
            $cancel_html = <<<CAN
        <div class='row'><p class='col-xs-12'><a href="/detail/confirm_order/{$order['id']}" class="btn col-xs-12 btn-danger" role="button">确认订单</a></p></div>
CAN;
        } else if ($order['status'] == 2) {
            $cancel_html = <<<CAN
        <div class='row'><p class='col-xs-12'>订单已确认配送</p></div>
CAN;
        }
        $order_addr = $order['order_info']['address'];
        $order_date = $order['order_info']['date'];
        $temp = <<<eto
<div class="row">
  <div class="col-sm-6 col-xs-12">
    <div class="thumbnail">
      <div class="caption">
        $info
        <div class='row'><p class='text-right col-xs-12'>合计<strong>{$order['total_price']}</strong>元</p></div>
        <div class='row'><p class='text-left col-xs-12'>地址：<strong>{$order_addr}</strong></p></div>
        <div class='row'><p class='text-left col-xs-12'>时间：<strong>{$order_date}</strong></p></div>
        $cancel_html
      </div>
    </div>
  </div>
</div>
eto;
        $infos .= $temp;
    }
} else {
    $infos = <<<eto
<div class="row">
  <div class="col-sm-6 col-xs-12">
    <div class="thumbnail">
      <div class="caption">
        <h3>还没有订单！</h3>
      </div>
    </div>
  </div>
</div>
eto;
}

$js =<<<JS
    $('#for_ajax_div').click(function(event){
        var ev = event.target || event.srcElement;
        if (ev.tagName == 'A'){
            url = $(ev).attr('href');
            $('#for_ajax_div').load(url);
            return false;
        }
    });
JS;
$config['dom_ready'][] = $js;
?>

<div id="wrap">

    <!-- Begin page content -->
    <div class="container">
        <div class="page-header">
            <h1>订单列表</h1>
        </div>
        <?php echo $infos; ?>
    </div>
</div>

