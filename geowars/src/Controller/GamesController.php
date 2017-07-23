<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\Event;
class GamesController extends AppController
{
	
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
       // $this->Auth->allow(['get']);
      //  $this->loadComponent('RequestHandler');
        $this->Auth->allow('add');
    }
    
    //Will eventualy take one vairable for the games ID
    public function view()
    {
        
    }
    
    //Shows a list of games currently in
    public function myGames()
    {
        
    }
    
    //List games that have not filled up with users yet that are open to join
    public function find()
    {
        
    }
    
    //Allows a user to create a new game
    public function create()
    {
        
    }
    
    
}
?>