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
        $this->Auth->allow(array('add','findAll','createProcess','join'));
    }
    
    //Will eventualy take one vairable for the games ID
    public function view()
    {
        
        
    }
    
        public function mygames()
    {
        
        
    }
    
    //Shows a list of games currently in
    public function ShowMyGames()
    {
        //For Ajax requests
        $this->viewBuilder()->layout('ajax');

        $this->loadModel('GamesUsers');
       
        $MyJoinedGames = $this->GamesUsers
                             ->find()
                             ->where(['user_id' => $this->Auth->User('id')])
                             ->all()->toArray();
        $gamesList;
        for($i = 0; $i < count($MyJoinedGames); $i++){
         
            $this->loadModel('Games');
            $games = $this->Games
                          ->find()
                          ->where(['id' => $MyJoinedGames[$i]->game_id]) // add max player calculations
                          ->order(['start_time' => 'ASC'])
                          ->all()->toArray();
         
            for($j = 0; $j < count($games); $j++ ){
                $this->loadModel('GamesUsers');
                $joinedPlayers = $this->GamesUsers
                                     ->find()
                                     ->where(['game_id' => $games[$j]->id])
                                     ->all()->toArray();
                $games[$j]->currentPlayers = count($joinedPlayers);

                $this->loadModel('Users');
                $playerName = $this->Users
                                   ->find()
                                   ->where(['id' => $games[$j]->created_by])
                                   ->all()
                                   ->toArray();
                $games[$j]->created_by = $playerName[0]->username;
            }
            $gamesList[$i] = $games[0]; // should only ever be one game. Should remove for loop since it's unnecessary.
                   
        }
        
        $this->set('games', $gamesList);
        
    }
    
    public function open()
    {
        
    }
    
    
    public function find()
    {
        
    }
    
    public function join()
    {
                //If request is post then create new game.      
        if ($this->request->is('post')) {
            
            //For Ajax requests
            $this->viewBuilder()->layout('ajax');
        
      
            $this->loadModel('GamesUsers');
           
            $joinedPlayers = $this->GamesUsers
                                 ->find()
                                 ->where(['game_id' => $this->request->data['game_id']])
                                 ->all()->toArray();
           $alreadyJoined = false;
           for($i = 0; $i < count($joinedPlayers); $i++){
           
               if ($joinedPlayers[$i]->user_id == $this->Auth->User('id')) {    
                    $alreadyJoined = true;
               }
           }
           
           if(!$alreadyJoined){
              $Gameusers = TableRegistry::get('GamesUsers');
              $newPlayer = $Gameusers->newEntity();
              $newPlayer->user_id = $this->Auth->User('id');
    				  $newPlayer->is_bot = 0; // alter logic here for when bots are implemented 0 = false 1 = true
    				  $newPlayer->game_id = $this->request->data['game_id'];
    				  $newPlayer->coins = 0;
    				  $newPlayer->troops = 0;
                      				  
    				  if ($Gameusers->save($newPlayer)) {
    				    $results = 1;
                            
                        }  else {
                            $results = 0;
                                
                            }
           }  else {
                $results = 0;
           }
        $this->set('results', $results);
        
        }
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
                    
        for($i = 0; $i < count($games); $i++ ){
            $this->loadModel('GamesUsers');
            $joinedPlayers = $this->GamesUsers
                                 ->find()
                                 ->where(['game_id' => $games[$i]->id])
                                 ->all()->toArray();
            $games[$i]->currentPlayers = count($joinedPlayers);
            $this->loadModel('Users');
            $playerName = $this->Users
                               ->find()
                               ->where(['id' => $games[$i]->created_by])
                               ->all()
                               ->toArray();
            $games[$i]->created_by = $playerName[0]->username;
        }
        
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