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
      
      $color = 'red';
      if ($this->request->is('post')) {
          if($this->request->query('color') != null){
              $color = $this->request->query('color');
          }
      }
      $this->loadModel('Games');
      $result = $this->Games->find();
      
      $this->set('result', $result);
    }
    
    //Ajax action
    //Return: all the data for a given map
    //
    //Currently holds all data for one map. Plan to move data to new location and
    //make it an option to have mulitple maps.
    public function getMap()
    {
        
        //For Ajax requests
        $this->viewBuilder()->layout('ajax');
        
        $this->loadModel('Territories');
        $territories = $this->Territories
                            ->find()
                            ->where(['game_id' => 1, 'turn_id' => 1])
                            ->order(['tile_id' => 'ASC'])
                            ->all()->toArray();
        
        // Build array by tile ID type
        $territoryById = array();
        for ($i = 0; $i < 20; $i++) {
            $territoryById[$territories[$i]->tile_id] = $territories[$i];
        }
        
        //$this->set('testing',$territories[0]->num_troops);
        
      //Template to hold map data
      $map = array(
    
        //Points: an array to hold all the corners of the shape, does not close the shape
        //color: the color of the shape to be drawn
        //Center: hold the points averaged center for determining which shape was clicked on
        //Top hexagon 1st
        array(
            "points" => array( array('x' => 150, 'y' => 0), 
                        array( 'x' => 250, 'y' => 0),
                        array( 'x' => 300, 'y' => 116.666666667),
                        array( 'x' => 250, 'y' => 233.3333333334),
                        array( 'x' => 150, 'y' => 233.3333333334),
                        array( 'x'=> 100, 'y' => 116.666667)),
	        "color" => "red",
	        "center" => array( 'x' => 200, 'y' => 116.666667),
	        "troops" => $territoryById[0]->num_troops
        ),
        //Top upper trapizoid
        array(
            "points" => array( array('x' => 250, 'y' => 0), 
                        array( 'x' => 450, 'y' => 0),
                        array( 'x' => 400, 'y' => 116.666666667),
                        array( 'x' => 300, 'y' => 116.666666667)),
	        "color" => "orange",
	        "center" => array( 'x' => 350, 'y' => 58.33334),
	        "troops" => $territoryById[1]->num_troops
        ),
        //Top hexagon 2nd
        array(
            "points" => array( array('x' => 450, 'y' => 0), 
                        array( 'x' => 550, 'y' => 0),
                        array( 'x' => 600, 'y' => 116.666666667),
                        array( 'x' => 550, 'y' => 233.3333333334),
                        array( 'x' => 450, 'y' => 233.3333333334),
                        array( 'x'=> 400, 'y' => 116.666667)),
	        "color" => "red",
	        "center" => array( 'x' => 500, 'y' => 116.6666667),
	        "troops" => $territoryById[2]->num_troops
        ),
        //top lower triangle 1st
        array(
            "points" => array( array('x' => 100, 'y' => 116.666667), 
                        array( 'x' => 50, 'y' => 233.33334),
                        array( 'x' => 150, 'y' => 233.333334)),
	        "color" => "yellow",
	        "center" => array( 'x' => 100, 'y' => 194),
	        "troops" => $territoryById[3]->num_troops
        ),
        //top lower Trapizoid
        array(
            "points" => array( array('x' => 300, 'y' => 116.66667), 
                        array( 'x' => 400, 'y' => 116.666667),
                        array( 'x' => 450, 'y' => 233.3333334),
                        array( 'x'=> 250, 'y' => 233.33334)),
	        "color" => "orange",
	        "center" => array( 'x' => 350, 'y' => 175),
	        "troops" => $territoryById[4]->num_troops
        ),
        //top lower triangle 2nd
        array(
            "points" => array( array('x' => 600, 'y' => 116.666667), 
                        array( 'x' => 550, 'y' => 233.33334),
                        array( 'x' => 650, 'y' => 233.333334)),
	        "color" => "yellow",
	        "center" => array( 'x' => 600, 'y' => 194),
	        "troops" => $territoryById[5]->num_troops
        ),
        //middle hexagon 1
        array(
            "points" => array( array('x' => 50, 'y' => 233.333334), 
                        array( 'x' => 150, 'y' => 233.333334),
                        array( 'x' => 200, 'y' => 350),
                        array( 'x' => 150, 'y' => 466.6666667),
                        array( 'x' => 50, 'y' => 466.6666667),
                        array( 'x'=> 0, 'y' => 350)),
	        "color" => "red",
	        "center" => array( 'x' => 100, 'y' => 350),
	        "troops" => $territoryById[6]->num_troops
        ),
        //middle upper Rhombus 1
        array(
            "points" => array( array('x' => 150, 'y' => 233.333334), 
                        array( 'x' => 250, 'y' => 233.33334),
                        array( 'x' => 300, 'y' => 350),
                        array( 'x'=> 200, 'y' => 350)),
	        "color" => "blue",
	        "center" => array( 'x' => 225, 'y' => 291.666667),
	        "troops" => $territoryById[7]->num_troops
        ),
         //middle upper trapizoid
        array(
            "points" => array( array('x' => 250, 'y' => 233.333334), 
                        array( 'x' => 450, 'y' => 233.33334),
                        array( 'x' => 400, 'y' => 350),
                        array( 'x'=> 300, 'y' => 350)),
	        "color" => "orange",
	        "center" => array( 'x' => 350, 'y' => 291.666667),
	        "troops" => $territoryById[8]->num_troops
        ),
        //middle upper Rhombus 2
        array(
            "points" => array( array('x' => 450, 'y' => 233.333334), 
                        array( 'x' => 550, 'y' => 233.3333334),
                        array( 'x' => 500, 'y' => 350),
                        array( 'x'=> 400, 'y' => 350)),
	        "color" => "blue",
	        "center" => array( 'x' => 475, 'y' => 291.666667),
	        "troops" => $territoryById[9]->num_troops
        ),
        //middle lower trapizoid
        array(
            "points" => array( array('x' => 300, 'y' => 350), 
                        array( 'x' => 400, 'y' => 350),
                        array( 'x' => 450, 'y' => 466.666667),
                        array( 'x'=> 250, 'y' => 466.66667)),
	        "color" => "orange",
	        "center" => array( 'x' => 350, 'y' => 408.333334),
	        "troops" => $territoryById[10]->num_troops
        ),
        //middle lower Rhombus 1
        array(
            "points" => array( array('x' => 200, 'y' => 350), 
                        array( 'x' => 300, 'y' => 350),
                        array( 'x' => 250, 'y' => 466.666667),
                        array( 'x'=> 150, 'y' => 466.66667)),
	        "color" => "blue",
	        "center" => array( 'x' => 225, 'y' => 408.33334),
	        "troops" => $territoryById[11]->num_troops
        ),
        //middle lower Rhombus 2
        array(
            "points" => array( array('x' => 400, 'y' => 350), 
                        array( 'x' => 500, 'y' => 350),
                        array( 'x' => 550, 'y' => 466.66667),
                        array( 'x'=> 450, 'y' => 466.66667)),
	        "color" => "blue",
	        "center" => array( 'x' => 475, 'y' => 408.33334),
	        "troops" => $territoryById[12]->num_troops
        ),
        //middle hexagon 2
        array(
            "points" => array( array('x' => 550, 'y' => 233.333334), 
                        array( 'x' => 650, 'y' => 233.333334),
                        array( 'x' => 700, 'y' => 350),
                        array( 'x' => 650, 'y' => 466.6666667),
                        array( 'x' => 550, 'y' => 466.6666667),
                        array( 'x'=> 500, 'y' => 350)),
	        "color" => "red",
	        "center" => array( 'x' => 600, 'y' => 350),
	        "troops" => $territoryById[13]->num_troops
        ),
        // Bottom Upper Triangle 2
        array(
          "points" => array( array('x' => 150, 'y' => 466.6666667),
                      array('x' => 50, 'y' =>  466.6666667),
                      array('x' => 100, 'y' => 583.333334)),
          "color" => "yellow",
          "center" => array('x' => 100, 'y' => 505.5555556),
	      "troops" => $territoryById[14]->num_troops
          ),
          // Bottom Upper Triangle 2
          array(
            "points" => array( array('x' => 550, 'y' => 466.6666667),
                       array('x' => 650, 'y' => 466.6666667),
                       array('x' => 600, 'y' => 583.333334)),
            "color" => "yellow",
            "center" => array('x' => 600, 'y' => 505.5555556),
	        "troops" => $territoryById[15]->num_troops
          ),
          // Bottom Hexagon 1 
          array(
            "points" => array(array('x' => 150, 'y' => 466.6666667),
                        array('x' => 250, 'y' => 466.6666667),
                        array('x' => 300, 'y' => 583.3333334),
                        array('x' => 250, 'y' => 700),
                        array('x' => 150, 'y' => 700),
                        array('x' => 100, 'y' => 583.3333334)),
              "color" => "red",
              "center" => array('x' => 200, 'y' => 583.3333334),
	        "troops" => $territoryById[16]->num_troops
            ), 
            // Bottom Upper Trapizoid
            array(
              "points" => array( array('x' => 250, 'y' => 466.6666667),
                          array('x' => 450, 'y' => 466.6666667),
                          array('x' => 400, 'y' => 583.3333334),
                          array('x' => 300, 'y' => 583.3333334)),
              "color" => "orange",
              "center" => array('x' => 350, 'y' => 525),
	        "troops" => $territoryById[17]->num_troops
              ),
          // Bottom Lower Trapizoid
          array(
            "points" => array( array('x' => 300, 'y' => 583.3333334),
                        array('x' => 400, 'y' => 583.3333334),
                        array('x' => 450, 'y' => 700),
                        array('x' => 250, 'y' => 700)),
            "color" => "orange",
            "center" => array('x' => 350, 'y' => 641.6666667),
	        "troops" => $territoryById[18]->num_troops
            ),
          // Bottom Hexagon 2
          array(
            "points" => array(array('x' => 450, 'y' => 466.6666667),
                        array('x' => 550, 'y' => 466.6666667),
                        array('x' => 600, 'y' => 583.3333334),
                        array('x' => 550, 'y' => 700),
                        array('x' => 450, 'y' => 700),
                        array('x' => 400, 'y' => 583.3333334)),
            "color" => "red",
            "center" => array('x' => 500, 'y' => 583.3333334),
	        "troops" => $territoryById[19]->num_troops
          )
      );
        
        //Set the map array to be available in the view with name of map
        $this->set('map', $map);
    }
    
    
    //List games that have not filled up with users yet that are open to join
    public function calculate()
    {
        //For Ajax requests
        $this->viewBuilder()->layout('ajax');
        
        $Games = TableRegistry::get('Games');
        $Territories = TableRegistry::get('Territories');
        $GamesUsers = TableRegistry::get('GamesUsers');
        $Users = TableRegistry::get('Users');
        
        
        $gameResults = $Games->find()->
            where(["start_time <" => time() * 1000])->
            where(["started = 0" ]);
        //echo time();
        foreach ($gameResults as $game) {
            //echo $game;
            $gameUsers = $GamesUsers->find()->
                where(["game_id =" => $game->id]);
                //echo $gameUsers;
            foreach ($gameUsers as $gameUser) {
                $userInfo = $Users->find()->
                where(["id =" => $gameUser->user_id]);
                
                foreach ($userInfo as $user) {
                    echo $user->username;
                }
            }
            
            
        }
    }
}
?>