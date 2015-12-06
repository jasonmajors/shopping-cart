<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController 
{
    // Allows users who are not logged in to call the add method in the Users controller.
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('add', 'logout');
    }

    public function login() 
    {
        $this->layout = 'signin';
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                // Saving 'loggedIn' the session so we can check if a user is logged in from the navbar element
                // Alternatively, we could pass a loggedIn variable to each view from the controller
                $this->Session->write('loggedIn', True);
                return $this->redirect(array('controller' => 'products', 'action' => 'index')   );
            }
            $this->Flash->set('Invalid username or password, please try again');
        }
    }

    public function logout() 
    {
        $this->Session->delete('loggedIn');
        return $this->redirect($this->Auth->logout());
    }

    public function add() 
    {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->set('The user has been saved');
                return $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
            $this->Flash->set('The user could not be saved');   
        };
    }
    // Need to be able to update user info
    public function update() {

    }

}