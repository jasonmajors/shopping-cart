<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {
    // Allows users who are not logged in to call the add method in the Users controller.
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'logout');
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect(array('controller' => 'products', 'action' => 'index')   );
            }
            $this->Flash->set('Invalid username or password, please try again');
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function add() {
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