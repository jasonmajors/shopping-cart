<?php
App::uses('AppController', 'Controller');

class ProductsController extends AppController 
{   
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('view', 'browse');
    }

    public function index() 
    {
        $this->layout = 'bootstrap-index';
        $this->set('products', $this->Product->find('all'));
    }

    public function browse()
    {
        $this->layout = 'bootstrap';

        $this->paginate = array(
            'limit' => 3,
            'order' => array('name' => 'asc')
        );

        $products = $this->paginate('Product');
        $this->set('products', $products);
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
    }
};
    
?>