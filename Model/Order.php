<?php
class Order extends AppModel {
	public $validate = array(
		'zipcode' => array(
			'rule' => array('lengthBetween', 5, 5),
			'message' => 'Zipcode must be 5 digits'
		)
	);

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
				'with' => 'OrdersProducts',
				'foreignKey' => 'order_id',
				'associationForeignKey' => 'product_id',
				'unique' => False
			)
	);

/* 

Moved this to Models/OrdersProducts.php

	public function addProduct($order_id, $p_id) {
		$this->data['Order']['id'] = $order_id;
		$this->data['Product']['id'] = $p_id;
		$this->data['Product']['OrdersProduct']['qty'] = 2;
		$this->save($this->data);
	}

*/

}