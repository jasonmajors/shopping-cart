<?php
class Product extends AppModel {
	public $hasAndBelongsToMany = array(
	'Order' =>
		array(
			'className' => 'Order',
			'joinTable' => 'orders_products',
			'with' => 'OrdersProducts',
			'foreignKey' => 'product_id',
			'associationForeignKey' => 'order_id',
			'unique' => False
		)
	);

}

?>