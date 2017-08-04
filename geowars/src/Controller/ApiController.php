<?php
namespace App\Controller;
use App\Controller\AppController;
use App\Model\Entity\User;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
class ApiController extends AppController
{
	
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
       // $this->Auth->allow(['get']);
      //  $this->loadComponent('RequestHandler');
        $this->Auth->allow(array('getMap', 'postAction', 'getPhase'));
    }
    
    public function getPhase() {
        $this->viewBuilder()->layout('ajax');
        $phase = 'buy';
        if($this->request->query('owner') != null){
              $owner = $this->request->query('owner');
              if($owner == 'red'){
                  $phase = 'attack';
              }
        }
        $this->set('phase', $phase);
    }
    
    public function postAction()
    {
      $this->viewBuilder()->layout('ajax');
      $result = array("result" => "failed");
      $tileId = -1;
      $action = '';
      if ($this->request->is('post')) {
            if($this->request->query('action') != null){
                $action = $this->request->query('action');
            }
            if($this->request->query('tile_id') != null){
                $tileId = $this->request->query('tile_id');
            }
            if($action == "buy" && $tileId != -1){
                $result["result"] = "success";
                // Add code to update db record
            }
          
      }
      
      $this->set('result', $result);
    }
    
    //Ajax action
    //Return: all the data for a given map
    //
    //Currently holds all data for one map. Plan to move data to new location and
    //make it an option to have mulitple maps.
    public function getMap()
    {
        // Static ID for testing. we will pull this as a parameter
        $game_id = 1;
        
        //For Ajax requests
        $this->viewBuilder()->layout('ajax');
        
        $this->loadModel('Territories');
        $territories = $this->Territories
                            ->find()
                            ->where(['game_id' => $game_id, 'turn_id' => 1])
                            ->order(['tile_id' => 'ASC'])
                            ->all()->toArray();
        
        // Build array by tile ID type
        $territoryById = array();
        for ($i = 0; $i < 20; $i++) {
            $territoryById[$territories[$i]->tile_id] = $territories[$i];
        }
        
        $mapInfo = $this->getMapPoints();
        
        $colors = array(
            0 => "red",
            1 => "blue",
            2 => "green"
            );
        
        $game = array();
        $map = array();
        
        for($i = 0; $i < 20; $i++) {
            $map[$i] = array(
                "points" => $mapInfo['points'][$i],
                "color" => $colors[$territoryById[$i]->user_id],
                "center" => $mapInfo['centers'][$i],
                "troops" => $territoryById[$i]->num_troops
                );
        }
        $game["map"] = $map;
        
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $game_id])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        $game["phase"] = $gameInfo->current_phase;
        $game["currentTurn"] = $gameInfo->last_completed_turn_id + 1;
        
        //Set the map array to be available in the view with name of map
        $this->set('game', $game);
    }
    
    // This function will build an array of map points. To begin we will have
    // one map but with potential to add more
    public function getMapPoints() {
        $mapPoints = array();
        $mapPointCenters = array();
        
        $mapPoints[0] = array( array('x' => 150, 'y' => 0), 
                        array( 'x' => 250, 'y' => 0),
                        array( 'x' => 300, 'y' => 116.666666667),
                        array( 'x' => 250, 'y' => 233.3333333334),
                        array( 'x' => 150, 'y' => 233.3333333334),
                        array( 'x'=> 100, 'y' => 116.666667));
        $mapPointCenters[0] = array( 'x' => 200, 'y' => 116.666667);
        
        $mapPoints[1] = array( array('x' => 250, 'y' => 0), 
                        array( 'x' => 450, 'y' => 0),
                        array( 'x' => 400, 'y' => 116.666666667),
                        array( 'x' => 300, 'y' => 116.666666667));
        $mapPointCenters[1] = array( 'x' => 350, 'y' => 58.33334);                
        
        $mapPoints[2] = array( array('x' => 450, 'y' => 0), 
                        array( 'x' => 550, 'y' => 0),
                        array( 'x' => 600, 'y' => 116.666666667),
                        array( 'x' => 550, 'y' => 233.3333333334),
                        array( 'x' => 450, 'y' => 233.3333333334),
                        array( 'x'=> 400, 'y' => 116.666667));
        $mapPointCenters[2] = array( 'x' => 500, 'y' => 116.6666667);
        
        $mapPoints[3] = array( array('x' => 100, 'y' => 116.666667), 
                        array( 'x' => 50, 'y' => 233.33334),
                        array( 'x' => 150, 'y' => 233.333334));
        $mapPointCenters[3] = array( 'x' => 100, 'y' => 194);
        
        $mapPoints[4] = array( array('x' => 300, 'y' => 116.66667), 
                        array( 'x' => 400, 'y' => 116.666667),
                        array( 'x' => 450, 'y' => 233.3333334),
                        array( 'x'=> 250, 'y' => 233.33334));
        $mapPointCenters[4] = array( 'x' => 350, 'y' => 175);
        
        $mapPoints[5] = array( array('x' => 600, 'y' => 116.666667), 
                        array( 'x' => 550, 'y' => 233.33334),
                        array( 'x' => 650, 'y' => 233.333334));
        $mapPointCenters[5] = array( 'x' => 600, 'y' => 194);
        
        $mapPoints[6] = array( array('x' => 50, 'y' => 233.333334), 
                        array( 'x' => 150, 'y' => 233.333334),
                        array( 'x' => 200, 'y' => 350),
                        array( 'x' => 150, 'y' => 466.6666667),
                        array( 'x' => 50, 'y' => 466.6666667),
                        array( 'x'=> 0, 'y' => 350));
        $mapPointCenters[6] = array( 'x' => 100, 'y' => 350);
        
        $mapPoints[7] = array( array('x' => 150, 'y' => 233.333334), 
                        array( 'x' => 250, 'y' => 233.33334),
                        array( 'x' => 300, 'y' => 350),
                        array( 'x'=> 200, 'y' => 350));
        $mapPointCenters[7] =array( 'x' => 225, 'y' => 291.666667);
        
        $mapPoints[8] = array( array('x' => 250, 'y' => 233.333334), 
                        array( 'x' => 450, 'y' => 233.33334),
                        array( 'x' => 400, 'y' => 350),
                        array( 'x'=> 300, 'y' => 350));
        $mapPointCenters[8] = array( 'x' => 350, 'y' => 291.666667);
        
        $mapPoints[9] = array( array('x' => 450, 'y' => 233.333334), 
                        array( 'x' => 550, 'y' => 233.3333334),
                        array( 'x' => 500, 'y' => 350),
                        array( 'x'=> 400, 'y' => 350));
        $mapPointCenters[9] = array( 'x' => 475, 'y' => 291.666667);
        
        $mapPoints[10] = array( array('x' => 300, 'y' => 350), 
                        array( 'x' => 400, 'y' => 350),
                        array( 'x' => 450, 'y' => 466.666667),
                        array( 'x'=> 250, 'y' => 466.66667));
        $mapPointCenters[10] = array( 'x' => 350, 'y' => 408.333334);
        
        $mapPoints[11] = array( array('x' => 200, 'y' => 350), 
                        array( 'x' => 300, 'y' => 350),
                        array( 'x' => 250, 'y' => 466.666667),
                        array( 'x'=> 150, 'y' => 466.66667));
        $mapPointCenters[11] = array( 'x' => 225, 'y' => 408.33334);
        
        $mapPoints[12] = array( array('x' => 400, 'y' => 350), 
                        array( 'x' => 500, 'y' => 350),
                        array( 'x' => 550, 'y' => 466.66667),
                        array( 'x'=> 450, 'y' => 466.66667));
        $mapPointCenters[12] = array( 'x' => 475, 'y' => 408.33334);
        
        $mapPoints[13] = array( array('x' => 550, 'y' => 233.333334), 
                        array( 'x' => 650, 'y' => 233.333334),
                        array( 'x' => 700, 'y' => 350),
                        array( 'x' => 650, 'y' => 466.6666667),
                        array( 'x' => 550, 'y' => 466.6666667),
                        array( 'x'=> 500, 'y' => 350));
        $mapPointCenters[13] = array( 'x' => 600, 'y' => 350);
        
        $mapPoints[14] = array( array('x' => 150, 'y' => 466.6666667),
                      array('x' => 50, 'y' =>  466.6666667),
                      array('x' => 100, 'y' => 583.333334));
        $mapPointCenters[14] = array('x' => 100, 'y' => 505.5555556);
        
        $mapPoints[15] = array( array('x' => 550, 'y' => 466.6666667),
                       array('x' => 650, 'y' => 466.6666667),
                       array('x' => 600, 'y' => 583.333334));
        $mapPointCenters[15] = array('x' => 600, 'y' => 505.5555556);
        
        $mapPoints[16] = array(array('x' => 150, 'y' => 466.6666667),
                        array('x' => 250, 'y' => 466.6666667),
                        array('x' => 300, 'y' => 583.3333334),
                        array('x' => 250, 'y' => 700),
                        array('x' => 150, 'y' => 700),
                        array('x' => 100, 'y' => 583.3333334));
        $mapPointCenters[16] = array('x' => 200, 'y' => 583.3333334);
        
        $mapPoints[17] = array( array('x' => 250, 'y' => 466.6666667),
                          array('x' => 450, 'y' => 466.6666667),
                          array('x' => 400, 'y' => 583.3333334),
                          array('x' => 300, 'y' => 583.3333334));
        $mapPointCenters[17] = array('x' => 350, 'y' => 525);
        
        $mapPoints[18] = array( array('x' => 300, 'y' => 583.3333334),
                        array('x' => 400, 'y' => 583.3333334),
                        array('x' => 450, 'y' => 700),
                        array('x' => 250, 'y' => 700));
        $mapPointCenters[18] = array('x' => 350, 'y' => 641.6666667);
        
        $mapPoints[19] = array(array('x' => 450, 'y' => 466.6666667),
                        array('x' => 550, 'y' => 466.6666667),
                        array('x' => 600, 'y' => 583.3333334),
                        array('x' => 550, 'y' => 700),
                        array('x' => 450, 'y' => 700),
                        array('x' => 400, 'y' => 583.3333334));
        $mapPointCenters[19] = array('x' => 500, 'y' => 583.3333334);
        
        $mapInfo = array();
        $mapInfo['points'] = $mapPoints;
        $mapInfo['centers'] = $mapPointCenters;
        
        return $mapInfo;
    }
    
    
    //List games that have not filled up with users yet that are open to join
    public function calculate()
    {
        //For Ajax requests
        $this->viewBuilder()->layout('ajax');
        
        //Set up connections to all requred tables
        $Games = TableRegistry::get('Games');
        $Territories = TableRegistry::get('Territories');
        $GamesUsers = TableRegistry::get('GamesUsers');
        $Users = TableRegistry::get('Users');
        
        
        //Find list of games that have not started but are part the set start time
        $gameResults = $Games->find()->
            where(["start_time <" => time() * 1000])->
            where(["started = 0" ]);
            
        
        $players = array();
        
        //Iterate through each game that needs to start
        foreach ($gameResults as $game) {
            
            echo "Game Found";
            
            //Uncomment below to see list of unstarted games that will be started
            //echo $game;
            
            //Array that will hold list of players for game
            $players = array();
            
            //Gets a list of users that are going to play current game
            $gameUsers = $GamesUsers->find()->
                where(["game_id =" => $game->id]);
            
            //Iterate through players and push to players array
            foreach ($gameUsers as $gameUser) {
                
                
                
                array_push($players, $gameUser->user_id);
                
            }
            
            
            echo " with " . count($players) . " players";
            
            //Hexagon locations for game start- 0, 2, 6, 13, 16, 19
            
            //Create 20 territores for our game
            for ($x = 0; $x < 20; $x++) {
                $newTerritory = $Territories->newEntity();
                
                $newTerritory->game_id = $game->id;
                $newTerritory->turn_id = 1;
                $newTerritory->tile_id = $x;
                
                if($x == 0 && count($players) > 0) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[0];
                    $newTerritory->num_troops = 20;
                } elseif ($x == 2 && count($players) > 1) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[1];
                    $newTerritory->num_troops = 20;
                } elseif ($x == 6 && count($players) > 2) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[2];
                    $newTerritory->num_troops = 20;
                } elseif ($x == 13 && count($players) > 3) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[3];
                    $newTerritory->num_troops = 20;
                } elseif ($x == 16 && count($players) > 4) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[4];
                    $newTerritory->num_troops = 20;
                } elseif ($x == 19 && count($players) > 5) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[5];
                    $newTerritory->num_troops = 20;
                } else {
                    $newTerritory->is_occupied = 0;
                    $newTerritory->user_id = 1;
                    $newTerritory->num_troops = 0;
                }
                
                echo ". Creating territory";  
                if(!$Territories->save($newTerritory)) {
                    //echo ".  Territory Created";
                    debug($this->validationErrors); die();
                }
                
               // $newTerritory->is_occupied =
                //$newTerritory->user_id =
                //$newTerritory->num_troops =
            }
            
            //Mark game as started
            $game->started = 1;
            
            //Save game state
            $Games->save($game);
            
        }
    }
}
?>