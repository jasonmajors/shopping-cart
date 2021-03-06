<?php
App::uses('AppController', 'Controller');

class OrdersController extends AppController 
{
    /**
    * Helper function to check if the user already has an open order
    *
    * @param int $user_id
    * @return boolean Whether or not the user has an open order
    */
    private function checkForOpenOrderID($user_id) 
    {
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

    /**
    * Quick helper function to check if an order belongs the logged in user
    *
    * @param int $order_id
    * @param string $status open or closed
    * @return boolean
    */
    private function orderMatchesUser($order_id, $status='open') 
    {
        $user_id = $this->Auth->user('id');
        $order = $this->Order->findById($order_id);

        if (($order['Order']['user_id'] == $user_id) && ($order['Order']['status'] == $status)) {
            return True;
        } else {
            return False;
        }
    }

    /**
    * Checks to see if a product already exists in a given order
    *
    * @param int $order_id
    * @param int $p_id Product id
    * @return boolean
    */
    private function isProductInOrder($order_id, $p_id) 
    {
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
    
    /**
    * Retrieves an array containing the qty of an entry in the orders_products table 
    * and the primary key of the entry (the id)
    *
    * @param int $order_id
    * @param int $p_id product id
    * @return array 
    */
    private function fetchProductQty($order_id, $p_id) 
    {
        // Method created in OrdersProducts. Returns the data array for the row in orders_products table given an order and product ID
        $entry = $this->Order->OrdersProducts->findProductEntry($order_id, $p_id);
        $current_qty = $entry[0]['OrdersProducts']['qty'];
        $entry_id = $entry[0]['OrdersProducts']['id'];

        return array($current_qty, $entry_id);
    }

    /**
    * Updates an already existing order
    *
    * @param int $order_id
    * @param int $p_id
    * @param int $qty Current quantity of the product in the order
    * @return Response Updates the order and redirects to the home page
    */
    private function updateOrder($order_id, $p_id, $qty) 
    {
        // Check to see if the product they're adding is already in the order
        if ($this->isProductInOrder($order_id, $p_id)) {
            // Retrieve the current qty value and the id of the entry from orders_products table so we can update it
            list($current_qty, $entry_id) = $this->fetchProductQty($order_id, $p_id);
            // Update the entry
            $data = array('id' => $entry_id, 'qty' => $current_qty + $qty);
            //TODO: Add fail case
            if ($this->Order->OrdersProducts->save($data)) {
                // Custom flash template created in ../lib/Cake/View/Elements/Flash/updated.ctp
                $this->Flash->updated('Order updated - View your Shopping Cart');
                
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
                $this->Flash->updated('Order updated - View your Shopping Cart');
                return $this->redirect(array('controller' => 'products', 'action' => 'index'));
            }
        // If the the validation rule in the OrdersProducts model failed
        } else {
            $this->Flash->set('Invalid item quantity');
            return $this->redirect(array('controller' => 'products', 'action' => 'index')); 
        }
    }

    /**
    * Create an Order or call updateOrder if the user already has an open order.
    *
    * @param Request POST data when a user attempts to add an item to their cart
    * @return Response redirects to home page
    */
    public function create() 
    {
        if (!$this->request->data) {
            throw new NotFoundException(__('Invalid product or order'));
        }
        // Retrieve the product_id and qty from View/Product/Index.ctp POST request
        $p_id = $this->request->data['OrdersProducts']['product_id'];
        $qty = $this->request->data['OrdersProducts']['qty'];
        $user_id = $this->Auth->user('id');
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

                $this->Flash->updated('New Order Created - View your Shopping Cart');
                
                return $this->redirect(array('controller' => 'products', 'action' => 'index'));

            } else {
                $this->Flash->set('Unable to create a new order');
            }
        }
    }

    /**
    * Returns an array containing the subtotal, tax, and total as a string in x,xxx.xx format
    *
    * @param Order $order
    * @param float $taxrate
    * @return array Contains the tax, subtotal, and total values for an order in string format
    */
    private function getOrderTotalsArray($order, $taxrate) {
        $order_totals = array();
        $subtotal = 0;

        foreach ($order['Product'] as $product) {
            $subtotal = $subtotal + ($product['price'] * $product['OrdersProducts']['qty']);
        }

        $tax = $subtotal * $taxrate;
        $total = $subtotal + $tax;

        $order_totals['tax'] = number_format((float)$tax, 2, '.', ',');
        $order_totals['subtotal'] = number_format((float)$subtotal, 2, '.', ',');
        $order_totals['total'] = number_format((float)$total, 2, '.', ',');

        return $order_totals;
    }

    /**
    * Set the Order items and totals for a user
    *
    * @param Request 
    * @return void
    */
    public function view() 
    {
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
        if ($order) {
            $order_totals = $this->getOrderTotalsArray($order, $taxrate = 0.085);

            $this->set('order', $order);
            $this->set('order_totals', $order_totals);
            $this->set('empty', False);
        } else {
            $this->set('empty', True);
        }
    }

    /**
    * Deletes a Product from an Order
    * 
    * @param int $order_id
    * @param int $p_id Product ID
    * @return Response refreshes the cart page after deleting the Product
    */
    public function deleteEntry($order_id, $p_id) 
    {
        $order_matches_user = $this->orderMatchesUser($order_id);
        // Make sure this order belongs to the logged in user and throw an error if it doesn't
        if (!$order_matches_user) {
            throw new UnauthorizedException(__('You are not authorized to modify this order'));
        }

        $entry = $this->Order->OrdersProducts->findProductEntry($order_id, $p_id);
        $this->Order->OrdersProducts->delete($id = $entry[0]['OrdersProducts']['id']);

        $order = $this->Order->findById($order_id);
        // Delete the order if the sole product is removed
        if (empty($order['Product'])) {
            $this->Order->delete($id = $order_id);
            return $this->redirect(array('controller' => 'orders', 'action' => 'view'));
        }
        // Update the orders table modified column
        $date = date('Y-m-d H:i:s');
        $this->Order->id = $order_id;
        $this->Order->saveField('modified', $date);

        return $this->redirect(array('controller' => 'orders', 'action' => 'view'));
    }

    /** 
    * Helper function for checkOut method below. Submits an order given an order ID
    *
    * @param int $order_id
    * @return Response
    */
    private function submitOrder($order_id) 
    {
        // Make sure it checks if the order belongs to the logged in user
        $order_matches_user = $this->orderMatchesUser($order_id);
        if (!$order_matches_user) {
            throw new ForbiddenException(__('Unauthorized attempted order submission'));
        }
        $order = $this->Order->findById($order_id);
        $order_totals = $this->getOrderTotalsArray($order, $taxrate=0.085);

        $this->Order->id = $order_id;
        // Set the modified date to the date the order is placed and close the order
        $this->Order->set(array(
            'modified' => date('Y-m-d H:i:s'),
            // $orders_totals['total'] is a string value of the total. Need to remove the "," and cast to float to store it as a decimal in the db
            'total' => (float)str_replace(",", "", $order_totals['total']),
            'status' => 'closed'
            )
        );
        $this->Order->save();
    }

    /**
    * Displays the order details and handles submission on POST request
    *
    * @param int $order_id
    * @return Response
    */
    public function checkOut($order_id=null) 
    {
        $this->layout = 'bootstrap';

        if (!$order_id) {
            throw new NotFoundException(__('Order not found'));
        }
        // Make sure it checks if the order belongs to the logged in user
        $order_matches_user = $this->orderMatchesUser($order_id);
        if (!$order_matches_user) {
            throw new ForbiddenException(__('Unauthorized checkout attempt'));
        }
        // Check for submission
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
        // Display the form
        $order = $this->Order->findById($order_id);

        if (!$order) {
            throw new NotFoundException(__('Order not found'));
        }
        // Data to prefill the checkout form
        $this->request->data('Order.shipping_firstname', $this->Auth->user('firstname'));
        $this->request->data('Order.shipping_lastname', $this->Auth->user('lastname'));
        $this->request->data('Order.shipping_address', $this->Auth->user('address'));
        $this->request->data('Order.shipping_city', $this->Auth->user('city'));
        $this->request->data('Order.shipping_state', $this->Auth->user('state'));
        $this->request->data('Order.shipping_zipcode', $this->Auth->user('zipcode'));
        $this->request->data('Order.billing_firstname', $this->Auth->user('firstname'));
        $this->request->data('Order.billing_lastname', $this->Auth->user('lastname'));
        $this->request->data('Order.billing_address', $this->Auth->user('address'));
        $this->request->data('Order.billing_city', $this->Auth->user('city'));
        $this->request->data('Order.billing_state', $this->Auth->user('state'));
        $this->request->data('Order.billing_zipcode', $this->Auth->user('zipcode'));

        $order_totals = $this->getOrderTotalsArray($order, 0.085);

        $this->set('order', $order);
        $this->set('order_totals', $order_totals);
    }

    /**
    * Displays the authenticated user's closed orders
    *
    * @param Request
    * @return Response
    */
    public function myOrders() 
    {
        $this->layout = 'bootstrap';
        $user_id = $this->Auth->user('id');
        $this->Paginator->settings = array(
            'conditions' => array(
                            'Order.user_id' => $user_id,
                            'Order.status' => 'closed' 
                        ),
            'limit' => 10,
            'order' => array('modified' => 'desc')
        );  
       
        $orders = $this->paginate('Order');
        $this->set('orders', $orders);
    }

    /**
    * Views the order details for a given closed order
    *
    * @param int $order_id
    * @return Response
    */
    public function viewOrder($order_id=null) 
    {
        $this->layout = 'bootstrap';

        if (!$order_id) {
            throw new NotFoundException(__('Invalid product'));
        }
        
        $order_matches_user = $this->orderMatchesUser($order_id, $status='closed');
        if (!$order_matches_user) {
            throw new NotFoundException(__('Unauthorized attempt to view order'));
        }

        $order = $this->Order->findById($order_id);
        $this->set('order', $order);
    }
}

?>