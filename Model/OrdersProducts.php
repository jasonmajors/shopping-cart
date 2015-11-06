<?php
class OrdersProducts extends AppModel {
    public $validate = array(
        'qty' => array(
            'rule' => array('comparison', '>=', 1),
        ),
        // TODO - Look this over
        'product_id' => array(
            'rule' => 'notBlank'
        )
    );

    public $belongsTo = array('Order', 'Product');

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