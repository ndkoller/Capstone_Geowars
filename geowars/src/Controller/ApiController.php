<?php
namespace App\Controller;
use App\Controller\AppController;
use App\Model\Entity\User;
use Cake\Event\Event;
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
	        "troops" => 20 
        ),
        //Top upper trapizoid
        array(
            "points" => array( array('x' => 250, 'y' => 0), 
                        array( 'x' => 450, 'y' => 0),
                        array( 'x' => 400, 'y' => 116.666666667),
                        array( 'x' => 300, 'y' => 116.666666667)),
	        "color" => "orange",
	        "center" => array( 'x' => 350, 'y' => 58.33334),
	        "troops" => 19
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
	        "troops" => 18
        ),
        //top lower triangle 1st
        array(
            "points" => array( array('x' => 100, 'y' => 116.666667), 
                        array( 'x' => 50, 'y' => 233.33334),
                        array( 'x' => 150, 'y' => 233.333334)),
	        "color" => "yellow",
	        "center" => array( 'x' => 100, 'y' => 194),
	        "troops" => 17
        ),
        //top lower Trapizoid
        array(
            "points" => array( array('x' => 300, 'y' => 116.66667), 
                        array( 'x' => 400, 'y' => 116.666667),
                        array( 'x' => 450, 'y' => 233.3333334),
                        array( 'x'=> 250, 'y' => 233.33334)),
	        "color" => "orange",
	        "center" => array( 'x' => 350, 'y' => 175),
	        "troops" => 16
        ),
        //top lower triangle 2nd
        array(
            "points" => array( array('x' => 600, 'y' => 116.666667), 
                        array( 'x' => 550, 'y' => 233.33334),
                        array( 'x' => 650, 'y' => 233.333334)),
	        "color" => "yellow",
	        "center" => array( 'x' => 600, 'y' => 194),
	        "troops" => 15
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
	        "troops" => 14
        ),
        //middle upper Rhombus 1
        array(
            "points" => array( array('x' => 150, 'y' => 233.333334), 
                        array( 'x' => 250, 'y' => 233.33334),
                        array( 'x' => 300, 'y' => 350),
                        array( 'x'=> 200, 'y' => 350)),
	        "color" => "blue",
	        "center" => array( 'x' => 225, 'y' => 291.666667),
	        "troops" => 13
        ),
         //middle upper trapizoid
        array(
            "points" => array( array('x' => 250, 'y' => 233.333334), 
                        array( 'x' => 450, 'y' => 233.33334),
                        array( 'x' => 400, 'y' => 350),
                        array( 'x'=> 300, 'y' => 350)),
	        "color" => "orange",
	        "center" => array( 'x' => 350, 'y' => 291.666667),
	        "troops" => 12
        ),
        //middle upper Rhombus 2
        array(
            "points" => array( array('x' => 450, 'y' => 233.333334), 
                        array( 'x' => 550, 'y' => 233.3333334),
                        array( 'x' => 500, 'y' => 350),
                        array( 'x'=> 400, 'y' => 350)),
	        "color" => "blue",
	        "center" => array( 'x' => 475, 'y' => 291.666667),
	        "troops" => 11
        ),
        //middle lower trapizoid
        array(
            "points" => array( array('x' => 300, 'y' => 350), 
                        array( 'x' => 400, 'y' => 350),
                        array( 'x' => 450, 'y' => 466.666667),
                        array( 'x'=> 250, 'y' => 466.66667)),
	        "color" => "orange",
	        "center" => array( 'x' => 350, 'y' => 408.333334),
	        "troops" => 10
        ),
        //middle lower Rhombus 1
        array(
            "points" => array( array('x' => 200, 'y' => 350), 
                        array( 'x' => 300, 'y' => 350),
                        array( 'x' => 250, 'y' => 466.666667),
                        array( 'x'=> 150, 'y' => 466.66667)),
	        "color" => "blue",
	        "center" => array( 'x' => 225, 'y' => 408.33334),
	        "troops" => 9
        ),
        //middle lower Rhombus 2
        array(
            "points" => array( array('x' => 400, 'y' => 350), 
                        array( 'x' => 500, 'y' => 350),
                        array( 'x' => 550, 'y' => 466.66667),
                        array( 'x'=> 450, 'y' => 466.66667)),
	        "color" => "blue",
	        "center" => array( 'x' => 475, 'y' => 408.33334),
	        "troops" => 8
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
	        "troops" => 7
        ),
        // Bottom Upper Triangle 2
        array(
          "points" => array( array('x' => 150, 'y' => 466.6666667),
                      array('x' => 50, 'y' =>  466.6666667),
                      array('x' => 100, 'y' => 583.333334)),
          "color" => "yellow",
          "center" => array('x' => 100, 'y' => 505.5555556),
	      "troops" => 6
          ),
          // Bottom Upper Triangle 2
          array(
            "points" => array( array('x' => 550, 'y' => 466.6666667),
                       array('x' => 650, 'y' => 466.6666667),
                       array('x' => 600, 'y' => 583.333334)),
            "color" => "yellow",
            "center" => array('x' => 600, 'y' => 505.5555556),
	        "troops" => 5
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
	        "troops" => 4
            ), 
            // Bottom Upper Trapizoid
            array(
              "points" => array( array('x' => 250, 'y' => 466.6666667),
                          array('x' => 450, 'y' => 466.6666667),
                          array('x' => 400, 'y' => 583.3333334),
                          array('x' => 300, 'y' => 583.3333334)),
              "color" => "orange",
              "center" => array('x' => 350, 'y' => 525),
	        "troops" => 3
              ),
          // Bottom Lower Trapizoid
          array(
            "points" => array( array('x' => 300, 'y' => 583.3333334),
                        array('x' => 400, 'y' => 583.3333334),
                        array('x' => 450, 'y' => 700),
                        array('x' => 250, 'y' => 700)),
            "color" => "orange",
            "center" => array('x' => 350, 'y' => 641.6666667),
	        "troops" => 2
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
	        "troops" => 1
          )
      );
        
        //Set the map array to be available in the view with name of map
        $this->set('map', $map);
    }
    
}
?>