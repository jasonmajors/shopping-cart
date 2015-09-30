<?php
App::uses('AppController', 'Controller');

class OrdersController extends AppController {

    // Helper function to check if the user already has an open order
    private function checkForOpenOrderID($user_id) {
        $order = $this->Order->find('all',
                                array('conditions' => array('Order.user_id' => $user_id, 'Order.status' => 'open')
                                )
                            );

        if ($order) {
            $order_id = $order[0]['Order']['id'];
            return $order_id;
        }           
        else {
            return False;
        }
    }

    private function isProductInOrder($order_id, $p_id) {
        $order = $this->Order->findById($order_id);
        $products = $order['Product'];
        $product_found = False;

        foreach ($products as $product) {
            if ($product['id'] == $p_id) {
                $product_found = True;
            }
        }
        return $product_found;
    }

    public function create($p_id) {
        $user_id = $this->Auth->user('id');
        $open_order_id = $this->checkForOpenOrderID($user_id);

        if ($open_order_id) {
            // addProduct() method created in Models/OrdersProducts.php
            $this->Order->OrdersProducts->addProduct($open_order_id, $p_id); 
            if ($this->Order->saveAll()) {
                $this->Flash->set('Order Updated');
                return $this->redirect(array('controller' => 'products', 'action' => 'index'));
            }
 
        } else {
            // No open order so we'll create an Order and add the Product
            $this->Order->create();
            // Data must be formmated this way to be saved into the orders_products table
            $data = array(
                'Order' => array(
                    'status' => 'open',
                    'user_id' => $user_id
                ),
                'Product' => array(
                    'id' => $p_id
                )
            );
            // This will create an order in the orders table and automatically create an entry in the orders_products
            // table, but we need to manually populate the qty column
            if ($this->Order->saveAll($data)) {
                $order_id = $this->Order->id;
                // This will return the array of the row created in orders_products. We're interested in the id column
                // so we can update the qty value
                $entry = $this->Order->OrdersProducts->find('all',
                                                    array('conditions' => array(
                                                                            'OrdersProducts.order_id' => $order_id, 
                                                                            'OrdersProducts.product_id' => $p_id
                                                        )
                                                    )
                                                );

                // Update the row with the qty value
                $data = array('id' => $entry[0]['OrdersProducts']['id'], 'qty' => 3);
                $this->Order->OrdersProducts->save($data);

                $this->Flash->set('New Order Created');
                
                return $this->redirect(array('controller' => 'products', 'action' => 'index'));

            } else {
                $this->Flash->set('Unable to create a new order');
            }
        }
    }

    # TODO: Check if logged in user is the user_id arg
    public function view($user_id) {
        $order = $this->Order->find('all',
                                array('conditions' => array('Order.user_id' => $user_id, 'Order.status' => 'open')
                                )
                            );

        $this->set('order', $order);

    }
}

?>