<?php
App::uses('AppController', 'Controller');

class OrdersController extends AppController {

    public function create($p_id) {
        $user_id = $this->Auth->user('id');
        $this->Order->create();
            
        $data = array(
            'Order' => array(
                'status' => 'open',
                'user_id' => $user_id
                ),

            'Product' => array(
                'id' => $p_id,
                )
            );

        if ($this->Order->save($data)) {
            $this->Flash->set('New order created');
            return $this->redirect(array('controller' => 'products', 'action' => 'index'));
        }
        $this->Flash->set('Unable to create a new order');
    }

    public function view($id) {
        $this->set('order', $this->Order->findById($id));
    }
}

?>