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
?>


<div id="wrap">

    <!-- Begin page content -->
    <div class="container">
        <form id='form_id' class="form-horizontal" role="form" method='post' action='/index/order_confirm'>
            <input class='hidden' name='user_id' value='<?php echo $user_id; ?>'>
            <input class='hidden' name='index' value='<?php echo $index; ?>'>
            <div class="page-header">
                <h1>开心订单</h1>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>餐名</th>
                        <th>价格</th>
                        <th>数量</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $meal_str; ?>
                    <tr class=''>
                        <td colspan="4" class='col-xs-12'>
                            <div class="form-group">
                                <label for="disabledSelect">选择您的历史地址：</label>
                                <select id="select_addr_id" class="form-control">
                                    <?php echo $addrs_option; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="inputaddress" class="control-label">地址</label>
                                <div class="">
                                    <input type="text" class="form-control" id="inputaddress" name='inputaddress' placeholder="现在支持厦门软件二期片区">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputname" class="control-label">留名</label>
                                <div class="">
                                    <input type="text" class="form-control" id="inputname" name='inputname' placeholder="留下您的大名">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputphone" class="control-label">手机</label>
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
                                <label class=" control-label">合计：</label>
                                <div class="">
                                    <strong id='total_price' class="form-control-static">0</strong>元
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr><td colspan="4" class='col-xs-12'>
                            <div class="form-group">
                                <button id="submit_modal" type='submit' class="btn btn-primary">提交订单</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

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
    if(addr_info.length == 3){
        var address = addr_info[0];
        var name = addr_info[1];
        var phoneno = addr_info[2];

        address = address.replace(/(^\s*)|(\s*$)/g, "");
        name = name.replace(/(^\s*)|(\s*$)/g, "");
        phoneno = phoneno.replace(/(^\s*)|(\s*$)/g, "");

        $("#inputaddress").val(address);
        $("#inputname").val(name);
        $("#inputphone").val(phoneno);
    }
}
        
$("#select_addr_id").change(function(){
        var selectcheckText=$("#select_addr_id").find("option:selected").text();
        if(selectcheckText){
            var addr_info = selectcheckText.split(",");
            if(addr_info.length == 3){
                var address = addr_info[0];
                var name = addr_info[1];
                var phoneno = addr_info[2];
        
                address = address.replace(/(^\s*)|(\s*$)/g, "");
                name = name.replace(/(^\s*)|(\s*$)/g, "");
                phoneno = phoneno.replace(/(^\s*)|(\s*$)/g, "");

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
        
        var i=1;
        for(;i < index;i++){
            var price = $("#count_id"+i).attr('price');
            var count = $("#count_id"+i).val();
            if( isNaN(count) || count < 0){
                count = 0;
            }
        
            total_price = total_price + parseFloat(price) * parseInt(count);
        }
        
        $('#total_price').text(total_price);
}
        
$(".count_class").change(function(){
        comulate_total();
});

eto;
$config['dom_ready'][] = $js;
?>