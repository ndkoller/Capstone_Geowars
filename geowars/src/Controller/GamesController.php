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
        //$this->Auth->allow(array('view','add','findAll','createProcess','join','ShowMyGames','botFill'));
        $this->set('username', $this->Auth->User('username'));
       //$this->Auth->User('id')
    

    }
    
    //Will eventualy take one vairable for the games ID
    public function view(){
        
    }
    
    
    public function open()
    {
        if ($this->request->is('post')) {
            
            //For Ajax requests
            $this->viewBuilder()->layout('ajax');
            $ID = $this->request->data['game_id'];
            // add some validation criteria
            
            $this->loadModel('Games');
            $games = $this->Games
                          ->find()
                          ->where(['id' => $ID]) 
                          ->order(['start_time' => 'ASC'])
                          ->all()->toArray();
            if($games[0]->started == 1){
                $results = 1;   
            }
            else{
                $this->Flash->error(__('Unable to open - Game has not started.'));
                $results = 0;
            }
            $this->set('results', $results);
        }
        
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
                          ->where(['id' => $MyJoinedGames[$i]->game_id, 'completed' => 0])
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
            if($games){
                $gamesList[$i] = $games[0]; // should only ever be one game. Will refactor to remove loop since it's unnecessary.    
            }
            else {
                $gamesList[$i] = null;
            }
            
                   
        }
        $this->set('games', $gamesList);
        
    }
    

    public function find()
    {
        
    }
    
    public function botFill($gameId){
            
        //Set up connections to all requred tables
        $Games = TableRegistry::get('Games');
        $Territories = TableRegistry::get('Territories');
        $GamesUsers = TableRegistry::get('GamesUsers');
        $Users = TableRegistry::get('Users');
        
        //If request is post then create new game.      
        $this->loadModel('GamesUsers');
           
        $GameJoin = $Games->find()
                              ->where(['id' => $gameId])
                              ->all()
                              ->toArray();
            
        $players = $this->GamesUsers
                        ->find()
                        ->where(['game_id' => $gameId])
                        ->all()
                        ->toArray();
        $alreadyJoined = false;
        $currentPlayers = count($players);
        $maxPlayers = $GameJoin[0]->max_users;
        $minPlayers = $GameJoin[0]->min_users;
            
            
        $botID = 11; // starting test user account to be used for bot players
        // ID's: 11-16 used for botID's available 
        while($currentPlayers < $maxPlayers){
            $Gameusers = TableRegistry::get('GamesUsers');
            $newPlayer = $Gameusers->newEntity();
            $newPlayer->user_id = $botID;
    	    $newPlayer->is_bot = 1; 
    	    $newPlayer->game_id = $gameId;
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
                      ->where(['game_id' => $gameId])
                      ->all()
                      ->toArray();
            for($j = 0; $j < count($PlayerTerritory); $j++){
                $newTerritory = $PlayerTerritory[$j];
                $x = $newTerritory->tile_id;
                if($x == 0 && $newTerritory->is_occupied == 0) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $botID;
                    $newTerritory->num_troops = 5;
                    $newPlayer->color = 'FireBrick';
                    if(!$Territories->save($newTerritory)) {
                        //echo ".  Territory Created";
                        debug($this->validationErrors); die();
                    }
                    break;
                } elseif ($x == 2 && $newTerritory->is_occupied == 0) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $botID;
                    $newTerritory->num_troops = 5;
                    $newPlayer->color = 'MediumBlue';
                    if(!$Territories->save($newTerritory)) {
                        //echo ".  Territory Created";
                        debug($this->validationErrors); die();
                    }
                    break;
                } elseif ($x == 6 && $newTerritory->is_occupied == 0) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $botID;
                    $newTerritory->num_troops = 5;
                    $newPlayer->color = 'Gold';
                    if(!$Territories->save($newTerritory)) {
                        //echo ".  Territory Created";
                        debug($this->validationErrors); die();
                    }
                    break;
                } elseif ($x == 13 && $newTerritory->is_occupied == 0) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $botID;
                    $newTerritory->num_troops = 5;
                    $newPlayer->color = 'DarkGreen';
                    if(!$Territories->save($newTerritory)) {
                        //echo ".  Territory Created";
                        debug($this->validationErrors); die();
                    }
                    break;
                } elseif ($x == 16 && $newTerritory->is_occupied == 0) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $botID;
                    $newTerritory->num_troops = 5;
                    $newPlayer->color = 'DarkOrange';
                    if(!$Territories->save($newTerritory)) {
                        //echo ".  Territory Created";
                        debug($this->validationErrors); die();
                    }
                    break;
                } elseif ($x == 19 && $newTerritory->is_occupied == 0) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $botID;
                    $newTerritory->num_troops = 5;
                    $newPlayer->color = 'Purple';
                    if(!$Territories->save($newTerritory)) {
                        //echo ".  Territory Created";
                        debug($this->validationErrors); die();
                    }
                    break;
                } else {
                    // failed to join because all starting territories were full.
                    // Change because it is overwritten below.
                    $results = 0; 
                }
    
            }
    
    		if ($Gameusers->save($newPlayer)) {
    		    $results = 1;
                if($currentPlayers+1 == $maxPlayers){
                    // if this most recent player addition hit Max then the game can start.

                }
            } else {
                // failed GameUser save
                $results = 0;
            }  
            // Progress the while loop
            $currentPlayers += 1;
            $botID += 1;
            
        }
        // finished filling game to max characters w/ bots
        // ready to start
        $GameJoin[0]->started = 1; 
        $Games->save($GameJoin[0]);
        
    }
    
    
    public function join($passedID = null)
    {
        
        //Set up connections to all requred tables
        $Games = TableRegistry::get('Games');
        $Territories = TableRegistry::get('Territories');
        $GamesUsers = TableRegistry::get('GamesUsers');
        $Users = TableRegistry::get('Users');
        $gameID;
         if ($this->request->is('post') && !($passedID)) {
             $gameID = $this->request->data['game_id'];
         }
         else{
             $gameID = $passedID;
         }
            //For Ajax requests
            $this->viewBuilder()->layout('ajax');
        
      
            $this->loadModel('GamesUsers');
           
            $GameJoin = $Games->find()
                              ->where(['id' => $gameID])
                              ->all()
                              ->toArray();
            
            $players = $this->GamesUsers
                                 ->find()
                                 ->where(['game_id' => $gameID])
                                 ->all()
                                 ->toArray();
           $alreadyJoined = false;
           $currentPlayers = count($players);
           $maxPlayers = $GameJoin[0]->max_users;
           $minPlayers = $GameJoin[0]->min_users;
           $botFill = $GameJoin[0]->atStart_opt;
           // 1 cancel game when start time is reached -> time calculations to be added in a refactor.
           // 2 fill game to minimum characters with bots
           // 3 fill game to maximum characters with bots.

           if($currentPlayers < $maxPlayers){ // Determines if space is available
                
                // Checks if current user belongs to game
                for($i = 0; $i < $currentPlayers; $i++){
                   
                       if ($players[$i]->user_id == $this->Auth->User('id')) {    
                            $alreadyJoined = true;
                       }
                   }
                  
                if(!$alreadyJoined){
                    $Gameusers = TableRegistry::get('GamesUsers');
                    $newPlayer = $Gameusers->newEntity();
                    $newPlayer->user_id = $this->Auth->User('id');
            	    $newPlayer->is_bot = 0; 
            	    $newPlayer->game_id = $gameID;
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
                              ->where(['game_id' => $gameID])
                              ->all()
                              ->toArray();
                    for($j = 0; $j < count($PlayerTerritory); $j++){
                        $newTerritory = $PlayerTerritory[$j];
                        $x = $newTerritory->tile_id;
                        if($x == 0 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'FireBrick';
                            if(!$Territories->save($newTerritory)) {
                                //echo ".  Territory Created";
                                debug($this->validationErrors); die();
                            }
                            break;
                        } elseif ($x == 2 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'MediumBlue';
                            if(!$Territories->save($newTerritory)) {
                                //echo ".  Territory Created";
                                debug($this->validationErrors); die();
                            }
                            break;
                        } elseif ($x == 6 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'Gold';
                            if(!$Territories->save($newTerritory)) {
                                //echo ".  Territory Created";
                                debug($this->validationErrors); die();
                            }
                            break;
                        } elseif ($x == 13 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'DarkGreen';
                            if(!$Territories->save($newTerritory)) {
                                //echo ".  Territory Created";
                                debug($this->validationErrors); die();
                            }
                            break;
                        } elseif ($x == 16 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'DarkOrange';
                            if(!$Territories->save($newTerritory)) {
                                //echo ".  Territory Created";
                                debug($this->validationErrors); die();
                            }
                            break;
                        } elseif ($x == 19 && $newTerritory->is_occupied == 0) {
                            $newTerritory->is_occupied = 1;
                            $newTerritory->user_id = $this->Auth->User('id');
                            $newTerritory->num_troops = 5;
                            $newPlayer->color = 'Purple';
                            if(!$Territories->save($newTerritory)) {
                                //echo ".  Territory Created";
                                debug($this->validationErrors); die();
                            }
                            break;
                        } else {
                            // failed to join because all starting territories were full.
                            $this->Flash->error(__('Failed to join - Territories are full.'));
                            // Change because it is overwritten below.
                            $results = 0; 
                        }

                    }

            		if ($Gameusers->save($newPlayer)) {
            		    $results = 1;
                        if($currentPlayers+1 == $maxPlayers){
                            // if this most recent player addition hit Max then the game can start.
                            $GameJoin[0]->started = 1; 
                            $Games->save($GameJoin);
                        }
                    } else {
                        // failed GameUser save
                        $this->Flash->error(__('Error with saving user account.'));
                        $results = 0;
                    }
                    
                } else {
                    // Failed Unique User Check
                    $this->Flash->error(__('Unable to join game - This account has already been added to this game'));
                    $results = 0;
                }     
           }
           else{
               // failed to add player current players add = or exceed MaxPlayers
               $this->Flash->error(__('Unable to join game - Game already has max players added'));
               $results = 0;
               $GameJoin[0]->started = 1; // Shouldn't be pulling into Join list so updating Started status here as an error correct.
               $Games->save($GameJoin[0]);
           }

           
           // Bot Fill Logic
            
        if($botFill == 2){
            // +1 for recently add player not in the current player count.
            if($currentPlayers+1 >= $minPlayers){
                $this->botFill($gameID);
                // Fill to minimum with bots
                // need to Refactor BotFill to take min fill or max fill
            }
        }
        elseif($botFill == 3){
            // Fill To Maximum Player with bots
            $this->botFill($gameID);
            

        }
        else{
            // bot fill == 1 
            // game will be deleted on start to refactor logic branch to utilize start time instead.
        }
           
        $this->set('results', $results);
        
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
            /*var postString = 'map=' + map + '&planningPhase=' + planningPhase + '&attackPhase=' + attackPhase
            + '&minPlayers=' + minPlayers + '&maxPlayers=' + maxPlayers + '&startUNIXTime=' + unixTime.getTime()  
            +  "&atStart=" + atStart + '&join=' + join; + '&botDifficulty=' + botDifficulty */     
          
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
            if($this->request->data['botDifficulty'] == 'hard'){
                $newGame->bot_hard_mode = 1;
            }
            else{
                $newGame->bot_hard_mode = 0;
            }
            $newGame->current_phase = 'attack'; 
            $newGame->last_completed_turn = 0;
            if ($Games->save($newGame)) {
    				    $results = 1;
    				    $this->Flash->error(__('Successfully created game.'));
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
                        if($this->request->data['join'] == 'join'){
                           $this->join($newGame->id);
                        }
                        
            }  else {
                $results = 0;
                $this->Flash->error(__('Unable to create game.'));    
            }

            $this->set('results', $results);
        }
    }
    
    
}
?>