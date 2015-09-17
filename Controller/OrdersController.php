<?php
App::uses('AppController', 'Controller');

class OrdersController extends AppController {

	public function create() {
		$user_id = $this->Auth->user('id');
		$this->Order->create();
		$this->Order->set('user_id', $user_id);
		$this->Order->set('status', 'open');

		if ($this->Order->save()) {
			$this->Flash->set('New order created');
			return $this->redirect(array('controller' => 'products', 'action' => 'index'));
		}
		$this->Flash->set('Unable to create a new order');

	}
}

?>