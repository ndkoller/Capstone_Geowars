<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\Event;
//use App\Model\Entity\Games;
use Cake\ORM\TableRegistry;
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
        $this->Auth->allow(array('add','findAll','createProcess'));
    }
    
    //Will eventualy take one vairable for the games ID
    public function view()
    {
        
        
    }
    
    //Shows a list of games currently in
    public function myGames()
    {
        
    }
    
    public function find()
    {
        
    }
    
    
    //List games that have not filled up with users yet that are open to join
    public function findAll()
    {
            //For Ajax requests
        $this->viewBuilder()->layout('ajax');
        
        $this->loadModel('Games');
        $games = $this->Games
                      ->find()
                      ->where(['started' => 0]) // add max player calculations
                      ->order(['start_time' => 'ASC'])
                      ->all()->toArray();
        
        $this->set('games', $games);
    }
    
    //Allows a user to create a new game
    public function create()
    {
        
    }
    
    //Allows a user to create a new game
    public function createProcess()
    {
        //If request is post then create new game.      
        if ($this->request->is('post')) {
            
            //For Ajax requests
            $this->viewBuilder()->layout('ajax');
        
        //echo "in post statement";
           // $this->loadModel('Games');
           
           $Games = TableRegistry::get('Games');
            
          //Values from create form post
          /*    var postString = 'map=' + map + '&planningPhase=' + planningPhase + '&attackPhase=' + attackPhase
        + '&minPlayers=' + minPlayers + '&maxPlayers=' + maxPlayers + '&startUNIXTime=' + unixTime.getTime()  
        +  "&atStart=" + atStart + '&join=' + join; */     
      
          $newGame = $Games->newEntity();
          $newGame->created_by = $this->Auth->User('id');
				  $newGame->completed = false;
				  $newGame->map = $this->request->data['map'];
				  $newGame->phase_one_duration = $this->request->data['planningPhase'];
				  $newGame->phase_two_duration = $this->request->data['attackPhase'];
				  $newGame->turn_end_time = $this->request->data['planningPhase'] + $this->request->data['attackPhase'];
				  $newGame->start_time = $this->request->data['startUNIXTime'];
				  $newGame->min_users = $this->request->data['minPlayers'];
  				  $newGame->max_users = $this->request->data['maxPlayers'];
  				  $newGame->atStart_opt = $this->request->data['atStart'];
                  $newGame->join_opt = $this->request->data['join'];
                  $newGame->current_phase = 'buy';
                  				  
				  if ($Games->save($newGame)) {
				    $results = 1;
                        //echo "success";
                        //$this->Flash->success(__('The game has been created.'));
                        //return $this->redirect(['action' => 'add']);
                    }  else {
                        $results = 1;
                        //$this->Flash->error(__('Unable to add the user.'));    
            }
            $this->set('results', $results);
        }
    }
    
    
}
?>