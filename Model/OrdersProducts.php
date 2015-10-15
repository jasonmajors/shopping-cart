<?php
class OrdersProducts extends AppModel {
	public $validate = array(
		'qty' => array(
			'rule' => 'notBlank'
		),
		'product_id' => array(
			'rule' => 'notBlank'
		)
	);

	public $belongsTo = array('Order', 'Product');

	public function addProduct($order_id, $p_id, $qty) {
		$this->data['order_id'] = $order_id;
		$this->data['product_id'] = $p_id;
		$this->data['qty'] = $qty;
		$this->save($this->data);
	}
}