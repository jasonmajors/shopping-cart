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

    public function create($p_id) {
        $user_id = $this->Auth->user('id');
        $open_order_id = $this->checkForOpenOrderID($user_id);

        if ($open_order_id) {
            $this->Order->addProduct($open_order_id, $p_id); 
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
                    'id' => $p_id,
                )
            );

            if ($this->Order->saveAll($data)) {
                $this->Flash->set('New order created');
                
                return $this->redirect(array('controller' => 'products', 'action' => 'index'));

            } else {
                $this->Flash->set('Unable to create a new order');
            }
        }
    }


    public function view($id) {
        $this->set('order', $this->Order->findById($id));
    }
}

?>