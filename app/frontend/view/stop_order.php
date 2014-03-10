<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$start_am = $data['start_am'];
$end_am = $data['end_am'];
$start_pm = $data['start_pm'];
$end_pm = $data['end_pm'];

$count = $data['count'];

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
                        <p>订餐时间为: <strong><?php echo $start_am;?></strong>至<strong><?php echo $end_am;?></strong><?php if($start_pm){ ?>, <strong><?php echo $start_pm;?></strong>至<strong><?php echo $end_pm;?></strong><?php } ?></p>
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
