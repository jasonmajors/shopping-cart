<?php
class Product extends AppModel {
	public $hasAndBelongsToMany = array(
	'Order' =>
		array(
			'className' => 'Order',
			'joinTable' => 'orders_products',
			'foreignKey' => 'product_id',
			'associationForeignKey' => 'order_id',
			'unique' => false
		)
	);

}

?>