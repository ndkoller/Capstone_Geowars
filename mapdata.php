<?php

//Template to hold map data
$map = array(
    
    //Points: an array to hold all the corners of the shape, does not close the shape
    //color: the color of the shape to be drawn
    //Center: hold the points averaged center for determining which shape was clicked on
    array(
        "points" => array( array(x => 0, y => 0), array( x => 50, y => 0), array( x=> 25, y => 43)),
	    "color" => "red",
	    "center" => array( x => 25, y => 14.333333333333334)
    )
);

echo json_encode($map);

?>