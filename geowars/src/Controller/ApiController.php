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
        array(
            "points" => array( array('x' => 0, 'y' => 0), array( 'x' => 50, 'y' => 0), array( 'x'=> 25, 'y' => 43)),
	        "color" => "red",
	        "center" => array( 'x' => 25, 'y' => 14.333333333333334)
        )
      );
        
        $this->set('map', $map);
    }
    
}
?>