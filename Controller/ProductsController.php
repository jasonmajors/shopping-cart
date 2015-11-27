<?php
App::uses('AppController', 'Controller');

class ProductsController extends AppController 
{
    public $helpers = array('Html', 'Form');

    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('view');
    }

    public function index() 
    {
        $this->layout = 'bootstrap-index';
        $this->set('products', $this->Product->find('all'));
    }

    public function view($id=null) 
    {
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