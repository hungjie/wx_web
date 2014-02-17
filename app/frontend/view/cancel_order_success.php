<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$user_id = $data['user_id'];

?>

<div id="wrap">

    <!-- Begin page content -->
    <div class="container">
        <div class="page-header">
            <h1>订单已取消</h1>
        </div>
        <a href="/?msisdn=<?php echo $user_id;?>" class="text-center">点击此处重新开始订单</a>
    </div>
</div>