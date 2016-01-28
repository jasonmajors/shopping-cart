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
        // Can change the category
        $featured_category_one = $this->Product->find('all',
                                                array('conditions' => array('category' => 'kayak'))
                                            );
        
        $featured_category_two = $this->Product->find('all',
                                                array('conditions' => array('category' => 'camera'))
                                            );

        $featured_category_three = $this->Product->find('all',
                                                array('conditions' => array('category' => 'backpack'))
                                            );

        $this->set('featured_category_one', $featured_category_one);
        $this->set('featured_category_two', $featured_category_two);
        $this->set('featured_category_three', $featured_category_three);
    }

    public function browse()
    {
        $this->layout = 'bootstrap';

        $this->paginate = array(
            'limit' => 10,
            'order' => array('name' => 'asc')
        );

        $products = $this->paginate('Product');
        $this->set('products', $products);
    }

    public function view($id=null) 
    {
        $this->layout = 'product';
        if (!$id) {
            throw new NotFoundException(__('Product not found'));
        }

        $product = $this->Product->findById($id);
        if (!$product) {
            throw new NotFoundException(__('Product not found'));
        }

        $this->set('product', $product);
    }
};
    
?>