<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$name = $data[1]['name'];
$price = $data[1]['price'];

$index = $data[0];
$input_id = "count_id$index";

$count_btn = "<button modify_id='$input_id' type='button' class='btn_jia btn btn-success btn-sm'>+</button>";
$count_btn .= "<input id='$input_id' name='count$index' class='count_class' price='$price' style='width:50%;' type='text' placeholder='数量' value='0'></input>";
$count_btn .= "<button modify_id='$input_id' type='button' class='btn_jian btn btn-success btn-sm'>-</button>";

$price_html = "<strong name='price$index'>$price</strong>元";

?>
<tr>
    <input type='hidden' name="price<?php echo $index;?>" value='<?php echo $price;?>'>
    <input type='hidden' name="name<?php echo $index;?>" value='<?php echo $name;?>'>
    <!--<td class='col-xs-1' style='padding: 5px;'><?php //echo $index;?></td>-->
    <td colspan='2' class='col-xs-4' style='padding: 3px;'><?php echo $name;?></td>
    <td class='col-xs-3' style='padding: 3px;'><?php echo $price_html;?></td>
    <td class='col-xs-5' style='padding: 3px;'><?php echo $count_btn; ?></td>
</tr>