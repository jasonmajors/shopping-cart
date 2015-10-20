<?php
class OrdersProducts extends AppModel {
	public $validate = array(
		'qty' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter a quantity'
			)
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

	public function findProductEntry($order_id, $p_id) {
		$product_entry = $this->find('all', array(
									'conditions' => array(
                                            'OrdersProducts.order_id' => $order_id, 
                                            'OrdersProducts.product_id' => $p_id
                                        )
                                    )
                                );    

		return $product_entry;
	}
}