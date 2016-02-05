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
                return $this->redirect(array('controller' => 'products', 'action' => 'index'));
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
        $this->layout = 'bootstrap';
        if ($this->request->is('post')) {
            // Check to make sure there isn't already a user registered under the desired email
            $email = $this->request->data['User']['email'];
            $email_unavailable = $this->User->find('first', array(
                                                    'conditions' => array(
                                                        'User.email' => $email
                                                        )
                                                    )
                                                );
            // Flash a message that the email is unavailable and reload the page
            if ($email_unavailable) {
                $this->Flash->set('That email address is already registered');
                $this->redirect(array('controller' => 'users', 'action' => 'add'));
            }

            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->set('The user has been saved');
                return $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
            $this->Flash->set('The user could not be saved');   
        };
    }
    // Need to be able to update user info
    public function update($user_id=null) {
        $this->layout = 'bootstrap';
        if (!$user_id) {
            throw new NotFoundException('That user could not be found');
        }
        // Check if user_id matches the user that is logged in
        $id = $this->Auth->user('id');
        if ($id != $user_id) {
            throw new ForbiddenException('Unauthorized attempt to modify user');
        }
        
        $user = $this->User->findById($user_id);
        if (!$user) {
                throw new NotFoundException('That user could not be found');
        }   
        if ($this->request->is(array('put', 'post'))) {
            $this->User->id = $user_id;
            if ($this->User->save($this->request->data)) {
                $this->Flash->set('Your information has been updated');
                return $this->redirect(array('controller' => 'products', 'action' => 'index'));    
            } else {
                $this->Flash->set('Unable to update your information');
            }
        }
        // Retrieve the user data to be updated
        if (!$this->request->data) {
            $this->request->data = $user;
            unset($this->request->data['User']['password']);
        }
    }

}