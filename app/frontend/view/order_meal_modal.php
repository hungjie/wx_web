<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$name = $data[1]['name'];
$price = $data[1]['price'];
$img_path = $data[1]['img_path'];

$index = $data[0];
$input_id = "count_id$index";

$count_btn = "<button modify_id='$input_id' type='button' class='btn_jia btn btn-success btn-sm'>+</button>";
$count_btn .= "<input id='$input_id' name='o[{$data[1]['id']}]' class='count_class' price='$price' style='width:40%;' type='text' placeholder='数量' value='0'></input>";
$count_btn .= "<button modify_id='$input_id' type='button' class='btn_jian btn btn-success btn-sm'>-</button>";

$price_html = "<strong name='price$index'>$price</strong>元";
?>
<tr style="border-bottom: 1px solid #ccc; ">
<input type='hidden' name="price<?php echo $index; ?>" value='<?php echo $price; ?>'>
<input type='hidden' name="name<?php echo $index; ?>" value='<?php echo $name; ?>'>
<!--<td class='col-xs-1' style='padding: 5px;'><?php //echo $index; ?></td>-->
<td colspan='2' class='col-xs-8' style='padding: 1px;'><?php //echo $name.$price_html; ?>
    <div class="media">
        <a class="pull-left" href="#">
            <img class="media-object" style="width: 64px; height: 64px;" src="<?php echo $img_path;?>">
        </a>
        <div class="media-body">
<?php echo "<p>$name</p>" . $price_html; ?>
        </div>
    </div>
</td>
<!--<td class='col-xs-2' style='padding: 1px;'><?php //echo $price_html; ?></td>-->
<td valign="middle" class='col-xs-4' style='padding: 1px;' ><div><?php echo $count_btn; ?></div></td>
</tr>