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
    // Quick helper function to check if an order matches the logged in user
    private function orderMatchesUser($order_id) {
        $user_id = $this->Auth->user('id');
        $order = $this->Order->findById($order_id);

        if ($order['Order']['user_id'] == $user_id) {
            return True;
        } else {
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
        // This product isn't in the order yet. Add it by creating the data array to save to the table.
        $data = array(
            'order_id' => $order_id, 
            'product_id' => $p_id,
            'qty' => $qty
        );

        if ($this->Order->OrdersProducts->save($data)) {
            // Get the current datetime to set the the orders' 'modified' value
            $date = date('Y-m-d H:i:s');
            $this->Order->id = $order_id;
            $this->Order->saveField('modified', $date);

            if ($this->Order->saveAll()) {
                $this->Flash->set('Order Updated');
                return $this->redirect(array('controller' => 'products', 'action' => 'index'));
            }
        } 
        // If the the validation rule in the OrdersProducts model failed
        else {
            $this->Flash->set('Invalid item quantity');
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
    // Return the order total as string xx.xx unless $as_float is set to true
    private function getOrderTotal($order, $as_float=False) {
        $order_total = 0;

        foreach ($order['Product'] as $product) {
            $order_total = $order_total + ($product['price'] * $product['OrdersProducts']['qty']);
        }
        if ($as_float) {
            $formmated_total = $order_total;
        }
        else {
            $formmated_total = number_format((float)$order_total,2, '.', ',');
        }
        return $formmated_total;
    }

    public function view() {
        $this->layout = 'bootstrap';        
        $user_id = $this->Auth->user('id');
        // There will only be one open order so use 'first'
        $order = $this->Order->find('first',
                                array(
                                    'conditions' => array(
                                        'Order.user_id' => $user_id, 
                                        'Order.status' => 'open'
                                        )
                                    )
                                );
        if (!$order) {
            throw new NotFoundException(__("You're cart is empty"));
        }

        $formmated_total = $this->getOrderTotal($order);
        $this->set('order', $order);
        $this->set('total', $formmated_total);
        $this->set('loggedIn', $this->Auth->loggedIn());
    }

    public function deleteEntry($order_id, $p_id) {
        $order_matches_user = $this->orderMatchesUser($order_id);
        // Make sure this order belongs to the logged in user and throw an error if it doesn't
        if (!$order_matches_user) {
            throw new NotFoundException(__('You are not authorized to modify this order'));
        }

        $entry = $this->Order->OrdersProducts->findProductEntry($order_id, $p_id);
        $this->Order->OrdersProducts->delete($id = $entry[0]['OrdersProducts']['id']);
        // Update the orders table modified column
        $date = date('Y-m-d H:i:s');
        $this->Order->id = $order_id;
        $this->Order->saveField('modified', $date);

        return $this->redirect(array('controller' => 'orders', 'action' => 'view'));
    }

    public function checkOut($order_id) {
        $this->layout = 'bootstrap';
        if ($this->request->is('post')) {
            $this->Order->id = $order_id;
            // Save the POST data
            if ($this->Order->save($this->request->data)) {
                // Order placed
                $this->Flash->set('Thank you, your order has been placed');
                $this->submitOrder($order_id);
                return $this->redirect(array('controller' => 'products', 'action' => 'index'));
            }
        }
        // Make sure it checks if the order belongs to the logged in user
        $order_matches_user = $this->orderMatchesUser($order_id);
        if (!$order_matches_user) {
            throw new NotFoundException(__('Unauthorized checkout attempt'));
        }

        $order_total = 0;
        $order = $this->Order->findById($order_id);

        if (!$order) {
            throw new NotFoundException(__('Order not found'));
        }

        $formmated_total = $this->getOrderTotal($order);

        //$this->set('total', $total);
        $this->set('order', $order);
        $this->set('total', $formmated_total);
        $this->set('loggedIn', $this->Auth->loggedIn());
    }

    private function submitOrder($order_id) {
        // Make sure it checks if the order belongs to the logged in user
        $order_matches_user = $this->orderMatchesUser($order_id);
        if (!$order_matches_user) {
            throw new NotFoundException(__('Unauthorized attempted order submission'));
        }
        $order = $this->Order->findById($order_id);
        $formmated_total = $this->getOrderTotal($order, $as_float=True);

        $this->Order->id = $order_id;
        // Set the modified date to the date the order is placed and close the order
        $this->Order->set(array(
            'modified' => date('Y-m-d H:i:s'),
            'total' => $formmated_total,
            'status' => 'closed'
            )
        );
        $this->Order->save();
    }

    public function myOrders() {
        $this->layout = 'bootstrap';
        $user_id = $this->Auth->user('id');
        $orders = $this->Order->find('all', array(
                                        'conditions' => array(
                                            'Order.user_id' => $user_id,
                                            'Order.status' => 'closed'
                                        )
                                    )
                                );

        $this->set('orders', $orders);
        $this->set('loggedIn', $this->Auth->loggedIn());
    }

    public function viewOrder($order_id) {
        $this->layout = 'bootstrap';
        $order_matches_user = $this->orderMatchesUser($order_id);
        if (!$order_matches_user) {
            throw new NotFoundException(__('Unauthorized attempt to view order'));
        }
        $order = $this->Order->findById($order_id);
        $this->set('order', $order);
        $this->set('loggedIn', $this->Auth->loggedIn());
    }
}

?>