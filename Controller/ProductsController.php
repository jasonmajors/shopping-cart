<?php
App::uses('AppController', 'Controller');

class ProductsController extends AppController {
    public $helpers = array('Html', 'Form');

    public function index() {
        $this->set('user_id', $this->Auth->user('id'));
        $this->set('products', $this->Product->find('all'));
    }
};
    
?>