<?php
class DBSchema{
    var $order_items = array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'int(11)',
    'null' => false,
    'key' => 'PRI',
    'default' => NULL,
  ),
  'user_order_id' => 
  array (
    'name' => 'user_order_id',
    'type' => 'int(11)',
    'null' => true,
    'key' => 'MUL',
    'default' => NULL,
  ),
  'product_id' => 
  array (
    'name' => 'product_id',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'tinyint(4)',
    'null' => true,
    'key' => '',
    'default' => '1',
  ),
  'product_type' => 
  array (
    'name' => 'product_type',
    'type' => 'tinyint(4)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'num' => 
  array (
    'name' => 'num',
    'type' => 'tinyint(4)',
    'null' => true,
    'key' => '',
    'default' => '1',
  ),
  'price' => 
  array (
    'name' => 'price',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'price2' => 
  array (
    'name' => 'price2',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'domestic_freight' => 
  array (
    'name' => 'domestic_freight',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'amount' => 
  array (
    'name' => 'amount',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'create_time' => 
  array (
    'name' => 'create_time',
    'type' => 'datetime',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'title' => 
  array (
    'name' => 'title',
    'type' => 'varchar(510)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'url' => 
  array (
    'name' => 'url',
    'type' => 'varchar(8190)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
);

var $product = array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'int(11)',
    'null' => false,
    'key' => 'PRI',
    'default' => NULL,
  ),
  'type' => 
  array (
    'name' => 'type',
    'type' => 'tinyint(4)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'owner_id' => 
  array (
    'name' => 'owner_id',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'price' => 
  array (
    'name' => 'price',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'create_time' => 
  array (
    'name' => 'create_time',
    'type' => 'datetime',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'title' => 
  array (
    'name' => 'title',
    'type' => 'varchar(255)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'image' => 
  array (
    'name' => 'image',
    'type' => 'varchar(255)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'url' => 
  array (
    'name' => 'url',
    'type' => 'varchar(8190)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
);

var $user = array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'int(11)',
    'null' => false,
    'key' => 'PRI',
    'default' => NULL,
  ),
  'level' => 
  array (
    'name' => 'level',
    'type' => 'tinyint(3) unsigned',
    'null' => true,
    'key' => '',
    'default' => '1',
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'tinyint(4)',
    'null' => false,
    'key' => '',
    'default' => '1',
  ),
  'amount' => 
  array (
    'name' => 'amount',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => '0',
  ),
  'create_time' => 
  array (
    'name' => 'create_time',
    'type' => 'datetime',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'last_time' => 
  array (
    'name' => 'last_time',
    'type' => 'datetime',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar(127)',
    'null' => false,
    'key' => 'UNI',
    'default' => NULL,
  ),
  'password' => 
  array (
    'name' => 'password',
    'type' => 'varchar(127)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'nick' => 
  array (
    'name' => 'nick',
    'type' => 'varchar(127)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'email' => 
  array (
    'name' => 'email',
    'type' => 'varchar(127)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'address' => 
  array (
    'name' => 'address',
    'type' => 'text',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
);

var $user_amount_log = array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'int(11)',
    'null' => false,
    'key' => 'PRI',
    'default' => NULL,
  ),
  'user_id' => 
  array (
    'name' => 'user_id',
    'type' => 'int(11)',
    'null' => true,
    'key' => 'MUL',
    'default' => NULL,
  ),
  'create_time' => 
  array (
    'name' => 'create_time',
    'type' => 'datetime',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'action' => 
  array (
    'name' => 'action',
    'type' => 'varchar(45)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'description' => 
  array (
    'name' => 'description',
    'type' => 'varchar(8190)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
);

var $user_order = array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'int(10) unsigned',
    'null' => false,
    'key' => 'PRI',
    'default' => NULL,
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'tinyint(4)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'user_id' => 
  array (
    'name' => 'user_id',
    'type' => 'int(11)',
    'null' => true,
    'key' => 'MUL',
    'default' => NULL,
  ),
  'shipping_fee' => 
  array (
    'name' => 'shipping_fee',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'items_amount' => 
  array (
    'name' => 'items_amount',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'final_amount' => 
  array (
    'name' => 'final_amount',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'local_currency_amount' => 
  array (
    'name' => 'local_currency_amount',
    'type' => 'int(11)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'items_fee' => 
  array (
    'name' => 'items_fee',
    'type' => 'float',
    'null' => true,
    'key' => '',
    'default' => '0.08',
  ),
  'currency_rate' => 
  array (
    'name' => 'currency_rate',
    'type' => 'float',
    'null' => true,
    'key' => '',
    'default' => '4.95',
  ),
  'create_time' => 
  array (
    'name' => 'create_time',
    'type' => 'datetime',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'address' => 
  array (
    'name' => 'address',
    'type' => 'varchar(510)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
);

var $user_order_log = array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'int(11)',
    'null' => false,
    'key' => 'PRI',
    'default' => NULL,
  ),
  'user_order_id' => 
  array (
    'name' => 'user_order_id',
    'type' => 'int(11)',
    'null' => true,
    'key' => 'MUL',
    'default' => NULL,
  ),
  'create_time' => 
  array (
    'name' => 'create_time',
    'type' => 'datetime',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'action' => 
  array (
    'name' => 'action',
    'type' => 'varchar(45)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
  'description' => 
  array (
    'name' => 'description',
    'type' => 'varchar(8190)',
    'null' => true,
    'key' => '',
    'default' => NULL,
  ),
);


}
?>

