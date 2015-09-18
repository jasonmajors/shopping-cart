<?php
class Order extends AppModel {
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public $hasAndBelongsToMany = array(
		'Product' =>
			array(
				'className' => 'Product',
				'joinTable' => 'orders_products',
				'foreignKey' => 'order_id',
				'associationForeignKey' => 'product_id',
			)
	);
}