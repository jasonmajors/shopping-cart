<?php
App::uses('AppController', 'Controller');

class ProductsController extends AppController {
	public $helpers = array('Html', 'Form');

	public function index() {
		$this->set('products', $this->Product->find('all'));
	}

};

?>