<?php
App::uses('AppController', 'Controller');

class ProductsController extends AppController {
    public $helpers = array('Html', 'Form');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view');
    }

    public function index() {
        $this->layout = 'bootstrap';
        
        $this->set('user_id', $this->Auth->user('id'));
        $this->set('products', $this->Product->find('all'));
        $this->set('loggedIn', $this->Auth->loggedIn());
    }

    public function view($id=null) {
        $this->layout = 'bootstrap';
        if (!$id) {
            throw new NotFoundException(__('Invalid product'));
        }

        $product = $this->Product->findById($id);
        if (!$product) {
            throw new NotFoundException(__('Invalid product'));
        }

        $this->set('product', $product);
        $this->set('loggedIn', $this->Auth->loggedIn());
    }
};
    
?>