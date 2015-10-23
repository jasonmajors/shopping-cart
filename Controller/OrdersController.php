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
    // TODO: Has to be a better way to check this. Should query the orders_products table directly instead of looping through the data structure returned by the framework
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

    private function fetchProductQty($order_id, $p_id) {
        // Method created in OrdersProducts. Returns the data array for the row in orders_products table given an order and product ID
        $entry = $this->Order->OrdersProducts->findProductEntry($order_id, $p_id);
        $current_qty = $entry[0]['OrdersProducts']['qty'];
        $entry_id = $entry[0]['OrdersProducts']['id'];

        return array($current_qty, $entry_id);
    }
    // Updates an already existing order
    private function updateOrder($order_id, $p_id, $qty) {
        // Check to see if the product they're adding is already in the order
        if ($this->isProductInOrder($order_id, $p_id)) {
            // Retrieve the current qty value and the id of the entry from orders_products table so we can update it
            list($current_qty, $entry_id) = $this->fetchProductQty($order_id, $p_id);
            // Update the entry
            $data = array('id' => $entry_id, 'qty' => $current_qty + $qty);
            //TODO: Add fail case
            if ($this->Order->OrdersProducts->save($data)) {
                $this->Flash->set('Order Updated!');
                
                return $this->redirect(array('controller' => 'products', 'action' => 'index')); 
            }
               
        }   
        // This product isn't in the order yet. Add it.
        // addProduct() method created in Models/OrdersProducts.php
        $this->Order->OrdersProducts->addProduct($order_id, $p_id, $qty + $current_qty); 
        // Get the current datetime to set the the orders' 'modified' value
        $date = date('Y-m-d H:i:s');
        $this->Order->id = $order_id;
        $this->Order->saveField('modified', $date);

        if ($this->Order->saveAll()) {
            $this->Flash->set('Order Updated');
            return $this->redirect(array('controller' => 'products', 'action' => 'index'));
        }
    }

    public function create() {
        if (!$this->request->data) {
            throw new NotFoundException(__('Invalid product or order'));
        }
        // Retrieve the product_id and qty from View/Product/Index.ctp POST request
        $p_id = $this->request->data['OrdersProducts']['product_id'];
        $qty = $this->request->data['OrdersProducts']['qty'];

        $user_id = $this->Auth->user('id');
        $current_qty = 0;
        // Check to see if this user already has an open order
        $open_order_id = $this->checkForOpenOrderID($user_id);
        if ($open_order_id) {
           // User has an open order. Update it.
            $this->updateOrder($open_order_id, $p_id, $qty);

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
                $entry = $this->Order->OrdersProducts->findProductEntry($order_id, $p_id);
                // Update the row with the qty value
                $data = array('id' => $entry[0]['OrdersProducts']['id'], 'qty' => $qty);
                // TODO: if->then
                $this->Order->OrdersProducts->save($data);

                $this->Flash->set('New Order Created');
                
                return $this->redirect(array('controller' => 'products', 'action' => 'index'));

            } else {
                $this->Flash->set('Unable to create a new order');
            }
        }
    }

    public function view() {
        $user_id = $this->Auth->user('id');

        $order = $this->Order->find('all',
                                array('conditions' => array('Order.user_id' => $user_id, 'Order.status' => 'open')
                                )
                            );
        $order_total =  0;

        foreach ($order[0]['Product'] as $product) {
            $order_total = $order_total + ($product['price'] * $product['OrdersProducts']['qty']);
            $formmated_total = number_format((float)$order_total,2, '.', ',');
        }

        $this->set('order', $order);
        $this->set('total', $formmated_total);
    }

    public function deleteEntry($order_id, $p_id) {
        $entry = $this->Order->OrdersProducts->findProductEntry($order_id, $p_id);
        $this->Order->OrdersProducts->delete($id = $entry[0]['OrdersProducts']['id']);

        return $this->redirect(array('controller' => 'orders', 'action' => 'view'));
    }
}

?>