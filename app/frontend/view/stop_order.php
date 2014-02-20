<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$start_am = $data['start'];
$end_am = $data['end'];

$count = $data['count'];

$data_start = date('Y-m-d H:i:s',$start_am);
$data_end= date('Y-m-d H:i:s', $end_am);

if($count > 0){
?>

<div class="container">
    <div class="page-header">
        <h1>非常抱歉<small></small></h1>
    </div>
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="thumbnail">
                <div class="caption">
                    <h4>您错过了今天的订餐时间</h4>
                        <p>我们的订餐时间为每天的 <strong><?php echo $data_start;?></strong>到 <strong><?php echo $data_end;?></strong></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php }else{ ?>

<div class="container">
    <div class="page-header">
        <h1>非常抱歉<small></small></h1>
    </div>
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="thumbnail">
                <div class="caption">
                    <h4>已达到今天的订餐份额。希望明天能为您服务！</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>
