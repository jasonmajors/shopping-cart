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
				'unique' => false
			)
	);

	public function addProduct($order_id, $p_id) {
		$this->data['Order']['id'] = $order_id;
		$this->data['Product']['id'] = $p_id;
		$this->save($this->data);
	}
}