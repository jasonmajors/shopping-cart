<?php
class OrdersProducts extends AppModel {
	public $belongsTo = array('Order', 'Product');

	public function addProduct($order_id, $p_id) {
		$this->data['order_id'] = $order_id;
		$this->data['product_id'] = $p_id;
		$this->data['qty'] = 2;
		$this->save($this->data);
	}
}