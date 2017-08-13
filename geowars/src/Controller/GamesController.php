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
            $gamesList[$i] = $games[0]; // should only ever be one game. Will refactor to remove loop since it's unnecessary.
                   
        }
        
        $this->set('games', $gamesList);
        
    }
    
    public function open()
    {
        
    }
    
    
    public function find()
    {
        
    }
    
    public function botFill($gameId){
        
    }
    
    public function join()
    {
        
        //Set up connections to all requred tables
        $Games = TableRegistry::get('Games');
        $Territories = TableRegistry::get('Territories');
        $GamesUsers = TableRegistry::get('GamesUsers');
        $Users = TableRegistry::get('Users');
        
        //If request is post then create new game.      
        if ($this->request->is('post')) {
            
            //For Ajax requests
            $this->viewBuilder()->layout('ajax');
        
      
            $this->loadModel('GamesUsers');
           
            $GameJoin = $Games->find()
                              ->where(['game_id' => $this->request->data['game_id']])
                              ->all();
            
            $players = $this->GamesUsers
                                 ->find()
                                 ->where(['game_id' => $this->request->data['game_id']])
                                 ->all()
                                 ->toArray();
           $alreadyJoined = false;
           $currentPlayers = count($players);
           $maxPlayers = $GameJoin->max_users;
           $minPlayers = $GameJoin->min_users;
           $botFill = $GameJoin->atStart_opt;
           // 1 cancel game when start time is reached -> time calculations to be added in a refactor.
           // 2 fill game to minimum characters with bots
           // 3 fill game to maximum characters with bots.

           if($currentPlayers < $maxPlayers){ // Determines if space is available
                
                // Checks if current user belongs to game
                for($i = 0; $i < $currentPlayers; $i++){
                   
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
            	    $newPlayer->troops = 5;
                    
                    // Hexagon locations for game start- 0, 2, 6, 13, 16, 19
                    // Starting position Colors
                    // Hexagon 0, Player 0 = FireBrick
                    // Hexagon 2, Player 1 = MediumBlue
                    // Hexagon 6, Player 2 = Gold
                    // Hexagon 13, Player 3 = DarkGreen
                    // Hexagon 16, Player 4 = DarkOrange
                    // Hexagon 19, Player 5 = Purple
                    
                    $PlayerTerritory = $Territories->find()
                              ->where(['game_id' => $this->request->data['game_id']])
                              ->all();
                    foreach($PlayerTerritory as $newTerritory){
                        $x = $newTerritory->id;
                        if($x == 0 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'FireBrick';
                        } elseif ($x == 2 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'MediumBlue';
                        } elseif ($x == 6 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'Gold';
                        } elseif ($x == 13 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'DarkGreen';
                        } elseif ($x == 16 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'DarkOrange';
                        } elseif ($x == 19 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'Purple';
                        } else {
                            // failed to join because all starting territories were full.
                            $results = 0; 
                        }
                        
                        if(!$Territories->save($newTerritory)) {
                            //echo ".  Territory Created";
                            debug($this->validationErrors); die();
                        }    
                    }
                    
                    
                                          				  
            		if ($Gameusers->save($newPlayer)) {
            		    $results = 1;
                    } else {
                        $results = 0;
                    }
                } else {
                    $results = 0;
                }     
           }
           else{
               // failed to add player current players add = or exceed MaxPlayers
               $results = 0;
               $GameJoin->started = 1; // Shouldn't be pulling into Join list so updating Started status
               $Games->save($GameJoin);
           }

           
           // Bot Fill Logic
                
                if($botFill == 2){
                    if($currentPlayers < $minPlayers){
                        
                    }
                }
                elseif($botFill == 3){
                    
                }
                else{
                    // bot fill == 1 
                    // game will be deleted on start to refactor logic branch to utilize start time instead.
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
                      ->where(['started' => 0])
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
            
         //Set up connections to all requred tables
        $Games = TableRegistry::get('Games');
        $Territories = TableRegistry::get('Territories');
        $GamesUsers = TableRegistry::get('GamesUsers');
        $Users = TableRegistry::get('Users');
            
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
        $newGame->current_phase = 'deploy';
        if ($Games->save($newGame)) {
				    $results = 1;
                    //Create 20 territores for our game
                    for ($x = 0; $x < 20; $x++) {
                        $newTerritory = $Territories->newEntity();
                        
                        $newTerritory->game_id = $newGame->id;
                        $newTerritory->tile_id = $x;
                        
                        $newTerritory->is_occupied = 0;
                        $newTerritory->user_id = 1;
                        $newTerritory->num_troops = 0;
                        
                        //echo ". Creating territory";  
                        if($Territories->save($newTerritory)) {
                            //echo ".  Territory Created";
                            //debug($this->validationErrors); die();
                        }
                      
                    }
                    //echo "success";
                    //$this->Flash->success(__('The game has been created.'));
                    //return $this->redirect(['action' => 'add']);
        }  else {
            $results = 0;
            //$this->Flash->error(__('Unable to add the user.'));    
        }

            $this->set('results', $results);
        }
    }
    
    
}
?>