<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
  <tr>
  <td>1</td>
  <td>Mark</td>
  <td>Otto</td>
  <td>@mdo</td>
  </tr>
 */

$start_order_time_am = $data['start_am'];
$end_order_time_am = $data['end_am'];
$start_order_time_pm = $data['start_pm'];
$end_order_time_pm = $data['end_pm'];

$order_count = $data['count'];

$meal_str = '';
$index = 1;
$user_id = $data['user_id'];
foreach ($data['meals'] as $meal) {
    $meal_str .= generate_html(array($index, $meal), 'order_meal_modal');
    $index++;
}

$addrs_option = '';
foreach ($data['addrs'] as $addr) {
    $address = "<option value=''>$addr</option>";
    $addrs_option .= $address;
}

$html_left = '';
if ($order_count <= 10) {
    $html_left = "<small><span class='label label-danger'>货源紧张</span></small>";
}
?>
<div class='container'>
    <div class="row">
        <a href='#' class="btn btn-warning col-xs-12" data-toggle="collapse" data-target="#demo">
            查看店铺信息
        </a>
    </div>
</div>

<div class="container">
    <div id="demo"  class="collapse row">
        <img data-src="" style="width:100%" src="/image/head.jpg">
        <div class="">
            <div class="thumbnail">
                <div class="caption">
                    <h3>店铺信息</h3>
                    <address><span class='glyphicon glyphicon-user'></span> <strong>名称：</strong>
                        好佳订餐
                    </address>
                    <address><span class='glyphicon glyphicon-cutlery'></span> <strong>地址：</strong>
                        湖里高科技园
                    </address>
                    <address><span class='glyphicon glyphicon-earphone'></span> <strong>联系电话：</strong>
                        <a href='#'>(123) 456-7890</a>
                    </address>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="wrap">
    <!-- Begin page content -->
    <div class="container">
        <form id='form_id' class="for_ajax form-horizontal" role="form" method='post' action='/index/order_confirm'>
            <input class='hidden' name='user_id' value='<?php echo $user_id; ?>'>
            <input class='hidden' name='index' value='<?php echo $index; ?>'>
            <div class="page-header">
                <h1>好佳订单(10份起送)<?php echo $html_left; ?></h1>
                <small>订餐时间：<strong><?php echo $start_order_time_am; ?></strong>至<strong><?php echo $end_order_time_am; ?></strong><?php if ($start_order_time_pm){ ?>, <strong><?php echo $start_order_time_pm; ?></strong>至<strong><?php echo $end_order_time_pm; ?></strong><?php } ?></small>
            </div>
            <table class="table-striped" style='width:100%;max-width: 100%;margin-bottom: 20px;border-spacing: 0;'>
                <thead>
                    <tr style="border-bottom: 1px solid #ccc;">
                        <th>餐名</th>
                        <th></th>
                        <th>数量</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $meal_str; ?>
                    <tr class=''>
                        <td colspan="3" class='col-xs-12'>
                            <div class="form-group">
                                <label for="disabledSelect">选择您的历史地址：</label>
                                <select id="select_addr_id" class="form-control">
                                    <?php echo $addrs_option; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputarea" class="control-label">片区：</label>
                                <select name='inputarea' id="inputarea" class="form-control">
                                    <option value='厦门软件园二期'>厦门软件园二期</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputaddress" class="control-label">地址：</label>
                                <div class="">
                                    <input type="text" class="form-control" id="inputaddress" name='inputaddress' placeholder="现在支持厦门软件二期片区，请填写楼栋信息。">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputname" class="control-label">留名：</label>
                                <div class="">
                                    <input type="text" class="form-control" id="inputname" name='inputname' placeholder="留下您的大名">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputphone" class="control-label">手机：</label>
                                <div class="">
                                    <input type="text" class="form-control" id="inputphone" name='inputphone' placeholder="联系号码">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label">支付方式：</label>
                                <div class="">
                                    <p class="form-control-static">货到付款</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label">合计(满10份减10元)：</label>
                                <div class="">
                                    <strong id='total_price' class="form-control-static">0</strong>元
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr><td colspan="3" class='col-xs-12'>
                            <div class="form-group ">
                                <button id="submit_modal" type='submit' class="btn col-xs-12 btn-primary" disabled="disabled">提交订单</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
<script>
    $('.for_ajax').ajaxForm(function(res) {
        $('#for_ajax_div').html(res);
    });
</script>

<?php
$js = <<<eto
$('.btn_jia').click(function(){
        var modify_id = $(this).attr("modify_id");
        var count = $("#"+modify_id).val();
        if(isNaN(count)){
            count = 0;
        }
        
        count ++;
        
        $("#"+modify_id).val(count);
        comulate_total();
});
        
$('.btn_jian').click(function(){
        var modify_id = $(this).attr("modify_id");
        var count = $("#"+modify_id).val();
        if(isNaN(count)){
            count = 0;
        }
        
        count --;
        
        if(count <= 0){
            count = 0;
        }
        
        $("#"+modify_id).val(count);
        comulate_total();
});
        
var selectcheckText=$("#select_addr_id").find("option:selected").text();
if(selectcheckText){
    var addr_info = selectcheckText.split(",");
    if(addr_info.length == 4){
        var area = addr_info[0];
        var address = addr_info[1];
        var name = addr_info[2];
        var phoneno = addr_info[3];

        area = area.replace(/(^\s*)|(\s*$)/g, "");
        address = address.replace(/(^\s*)|(\s*$)/g, "");
        name = name.replace(/(^\s*)|(\s*$)/g, "");
        phoneno = phoneno.replace(/(^\s*)|(\s*$)/g, "");

        $('#inputarea').val(area);
        $("#inputaddress").val(address);
        $("#inputname").val(name);
        $("#inputphone").val(phoneno);
    }
}
        
$("#count_id{$data['index']}").val('1');
comulate_total();
        
$("#select_addr_id").change(function(){
        var selectcheckText=$("#select_addr_id").find("option:selected").text();
        if(selectcheckText){
            var addr_info = selectcheckText.split(",");
            if(addr_info.length == 4){
                var area = addr_info[0];
                var address = addr_info[1];
                var name = addr_info[2];
                var phoneno = addr_info[3];
        
                area = area.replace(/(^\s*)|(\s*$)/g, "");
                address = address.replace(/(^\s*)|(\s*$)/g, "");
                name = name.replace(/(^\s*)|(\s*$)/g, "");
                phoneno = phoneno.replace(/(^\s*)|(\s*$)/g, "");

                $('#inputarea').val(area);
                $("#inputaddress").val(address);
                $("#inputname").val(name);
                $("#inputphone").val(phoneno);
            }
        }
});
        
$("#submit_modal").click(function(e){
        var count = $index;
        var check_count = false;
        var i=1
        for(; i<count;i++){
            var name = "count_id"+i;
            var val = $('#'+name).val();
            if( !isNaN(val) && val > 0){
                check_count = true;
                break;
            }
        }
        if(!check_count){
            alert('您还没确定订的数量');
            return false;
        }
        
        if( !$("#inputaddress").val() ){
            alert('您的地址还未写');
            return false;
        }
        if( !$("#inputname").val() ){
            alert('您的大名还未写');
            return false;
        }
        if( !$("#inputphone").val() ){
            alert('您的手机号码还未写');
            return false;
        }
});
        
function comulate_total(){
        var index = $index;
        var total_price = 0.00;
        
        var total_count = 0;
        
        var i=1;
        for(;i < index;i++){
            var price = $("#count_id"+i).attr('price');
            var count = $("#count_id"+i).val();
            if( isNaN(count) || count < 0){
                count = 0;
            }
            
            total_count += parseInt(count);
        
            total_price = total_price + parseFloat(price) * parseInt(count);
        }
        
        if (total_count >= 10){
            $('#submit_modal').attr('disabled', false);
            total_price -= parseInt(total_count / 10) * 10;
        }
        else $('#submit_modal').attr('disabled', true);
        
        $('#total_price').text(total_price);
}
        
$(".count_class").change(function(){
        comulate_total();
});

eto;
$config['dom_ready'][] = $js;
?>