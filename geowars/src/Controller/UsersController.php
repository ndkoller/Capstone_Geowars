<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class UsersController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(array('add', 'login', 'processAdd'));
    }

     public function index()
     {
        $this->set('users', $this->Users->find('all'));
    }

    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }
    
    public function add()
    {
        
    }

    public function processAdd()
    {
        $this->viewBuilder()->layout('ajax');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data());
            if ($this->Users->save($user)) {
                echo "1";
                $this->Flash->success('Registration was successful. You man now login.');
                //$this->Flash->success(__('The user has been saved.'));
                //return $this->redirect(['action' => 'add']);
            }
            else{
                echo "0";
               //$this->Flash->error(__('Unable to add the user.'));    
            }
        }
        $this->set('user', $user);
    }
    
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                $this->Flash->success('Login was successful');
                //echo "Logged in?";
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
            }
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

}

?>