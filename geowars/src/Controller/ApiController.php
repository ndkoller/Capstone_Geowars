<?php
namespace App\Controller;
use App\Controller\AppController;
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
        $this->Auth->allow('getMap');
    }
    
    public function getMap()
    {
        
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
	        "center" => array( 'x' => 200, 'y' => 116.666667)
        ),
        //Top upper trapizoid
        array(
            "points" => array( array('x' => 250, 'y' => 0), 
                        array( 'x' => 450, 'y' => 0),
                        array( 'x' => 400, 'y' => 116.666666667),
                        array( 'x' => 300, 'y' => 116.666666667)),
	        "color" => "orange",
	        "center" => array( 'x' => 325, 'y' => 58.33334)
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
	        "center" => array( 'x' => 500, 'y' => 116.6666667)
        ),
      );
        
        $this->set('map', $map);
    }
    
}
?>