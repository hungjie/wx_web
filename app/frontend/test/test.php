<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$td_order_info_temp = <<<ETO
<tr>
    <td colspan="%d" class="td_order_info">
        <div class="media">
            <a href="%s" target="_blank" class="pull-left">
                <img src="%s" alt="查看宝贝详情" style="width: 64px; height: 64px;">
            </a>
            <div class="media-body">
                <a href="%s" target="_blank">
                    %s
                </a>
            </div>
        </div>
    </td>
    <td >%s</td>
    <td >%s</td>
    <td >%s</td>
ETO;

$td_order_info = $td_order_info_temp;

$td_order_info = preg_replace('/%d/', 6, $td_order_info, 1);

echo $td_order_info;
?>
