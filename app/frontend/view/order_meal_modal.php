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
$count_btn .= "<input id='$input_id' name='count$index' class='count_class' price='$price' style='width:40%;' type='text' placeholder='数量' value='0'></input>";
$count_btn .= "<button modify_id='$input_id' type='button' class='btn_jian btn btn-success btn-sm'>-</button>";

$price_html = "<strong name='price$index'>$price</strong>元";
?>
<tr>
<input type='hidden' name="price<?php echo $index; ?>" value='<?php echo $price; ?>'>
<input type='hidden' name="name<?php echo $index; ?>" value='<?php echo $name; ?>'>
<!--<td class='col-xs-1' style='padding: 5px;'><?php //echo $index; ?></td>-->
<td colspan='2' class='col-xs-8' style='padding: 1px;'><?php //echo $name.$price_html; ?>
    <div class="media">
        <a class="pull-left" href="#">
            <img class="media-object" alt="64x64" style="width: 64px; height: 64px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAABN0lEQVR4Xu2YQQ6EIAxFdeXFODZnYu9qJk5C0sGiUiAx8FyKVPr76VPWEMJnmfhaEQAHsAXoARP3wIUmCAWgABSAAlBgYgXAIBgEg2AQDE4MAX6GwCAYBINgEAyCwYkVAIO1GPTe//nHOXfyU3xGG9PM1yNmzuRVDpCJ5ZKUyTwRoEfMqx3eTIBcJbdtW/Z9/w2XCtAqZncB5Atkkkc1NQFileVYFCi1fypcLqa1jzd1QM6+2va4EycKWRLTIkI3AY7FPKmmVmF5LxXvLmapCF0FiItp5QCZXClZulBAq/IVBtN9rvUAa8zSysfnqxxgfemb5iFA7Zfgm6ppWQsOwAEciXEkxpGYpXuOMgcKQAEoAAWgwCgd3ZIHFIACUAAKQAFL9xxlDhSAAlAACkCBUTq6JY/pKfAFwO6XkLwNdToAAAAASUVORK5CYII=">
        </a>
        <div class="media-body">
<?php echo "<p>$name</p>" . $price_html; ?>
        </div>
    </div>
</td>
<!--<td class='col-xs-2' style='padding: 1px;'><?php //echo $price_html; ?></td>-->
<td valign="middle" class='col-xs-4' style='padding: 1px;' ><div><?php echo $count_btn; ?></div></td>
</tr>