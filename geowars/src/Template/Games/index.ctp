<html>

<canvas id="canvas" width="700" height="700"></canvas>
</body>
</html>
 <script>

 //Establish the Canvas variable
   var canvas = document.getElementById('canvas');
   var ctx = canvas.getContext('2d');
 
 //Hold Shapes to draw board
 //Points: an array to hold all the corners of the shape, does not close the shape
 //color: the color of the shape to be drawn
 //Center: hold the points averaged center for determining which shape was clicked on
var shapes = [
	// top hexagon 1st
	{ points: [{ x: 150, y: 0}, { x: 250, y: 0},  { x: 300, y: 116.666666667},  { x: 250, y: 233.3333333334}, { x: 150, y: 233.3333333334}, { x: 100, y: 116.666666667} ],
	  color: "red",
	  center: { x: 200, y: 116.666666667}
	},
	// top upper trapizoid
	{ points: [{ x: 250, y: 0}, { x: 450, y: 0}, { x: 400, y: 116.666666667}, { x: 300, y: 116.666666667} ],
	  color: "orange",
	  center: { x: 325, y: 58.3333333334}
	},
	// top hexagon 2nd
	{ points: [{ x: 450, y: 0}, { x: 550, y: 0}, { x: 600, y: 116.666666667}, { x: 550, y: 233.3333333334}, { x: 450, y: 233.3333333334}, { x: 400, y: 116.666666667}],
	  color: "red",
	  center: { x: 500, y: 116.666666667}
	},
	// top lower triangle 1st
	{ points: [{ x: 100, y: 116.666666667}, { x: 50, y: 233.3333333334}, {x: 150, y: 233.3333333334}],
	  color: "yellow",
 	  center: { x: 100, y: 194}
	},
	// top lower Trapizoid
	{ points: [{ x: 300, y: 116.666666667}, {x: 400, y: 116.666666667}, { x: 450, y: 233.3333333334 }, {x: 250, y: 233.3333333334} ],
	  color: "orange",
	  center: { x: 375, y: 175},
	},
	// top lower triangle 2nd
	{ points: [{ x: 600, y: 116.666666667}, {x: 550, y: 233.3333333334}, {x: 650, y: 233.3333333334}],
	  color: "yellow",
 	  center: { x: 600, y: 194}
	},
	// middle hexagon 1
	{ points: [{ x: 50, y: 233.3333333334}, {x: 150, y: 233.3333333334}, { x: 200, y: 350}, {x: 150, y: 466.666666667}, {x: 50, y: 466.666666667}, { x: 0, y: 350}],
	  color: "red",
	  center: { x: 100, y: 350}
	},
	// middle upper Rhombus 1
	{ points: [{x: 150, y: 233.3333333334}, {x: 250, y: 233.3333333334}, { x: 300, y: 350}, { x: 200, y: 350} ],
	  color: "blue",
	  center: { x: 225, y: 291.666666667}
	},
	// middle upper trapizoid
	{ points: [{x: 250, y: 233.3333333334}, {x: 450, y: 233.3333333334}, { x: 400, y: 350}, { x: 300, y: 350} ],
	  color: "orange",
	  center: { x: 325, y: 291.666666667}
	},
	// middle upper Rhombus 2
	{ points: [{x: 450, y: 233.3333333334}, {x: 550, y: 233.3333333334}, { x: 500, y: 350}, { x: 400, y: 350} ],
	  color: "blue",
	  center: {x: 475, y: 291.666666667}
	},
	// middle lower trapizoid
	{ points: [{ x: 300, y: 350}, { x: 400, y: 350}, {x: 450, y: 466.666666667}, {x: 250, y: 466.666666667} ],
	  color: "Orange",
	  center: { x: 375, y: 233.3333333334}
	},
	// Middle lower Rhombus 1
	{ points: [{ x: 200, y: 350}, { x: 300, y: 350}, {x: 250, y: 466.666666667}, {x: 150, y: 466.666666667} ],
	  color: "blue",
	  center: { x: 225, y: 408.3333333334}
	},
	// Middle lower Rhombus 2
	{ points: [{ x: 400, y: 350}, { x: 500, y: 350}, {x: 550, y: 466.666666667}, {x: 450, y: 466.666666667}],
	  color: "blue",
	  center: { x: 475, y: 408.3333333334}
	},
	// middle hexagon 2
	{ points: [{x: 550, y: 233.3333333334}, {x: 650, y: 233.3333333334}, { x: 700, y: 350}, {x: 650, y: 466.666666667}, {x: 550, y: 466.666666667}, { x: 500, y: 350} ],
	  color: "red",
	  center: { x: 600, y: 350}
	},
	// Bottom Upper Triangle 1
	{ points: [{x: 150, y: 466.666666667}, {x: 50, y: 466.666666667}, { x: 100, y: 583.3333333334} ],
	  color: "yellow",
	  center: { x: 100, y: 505}
	},
	// Bottom Upper Triangle 2
	{ points: [{x: 550, y: 466.666666667}, {x: 650, y: 466.666666667}, { x: 600, y: 583.3333333334}],
	  color: "yellow",
	  center: { x: 600, y: 505}
	},
	// Bottom Hexagon 1
	{ points: [{x: 150, y: 466.666666667}, {x: 250, y: 466.666666667}, { x: 300, y: 583.3333333334}, { x: 250, y: 700}, {x: 150, y: 700}, { x: 100, y: 583.3333333334}],
	  color: "red",
	  center: { x: 200, y: 583.3333333334}
	},
	// Bottom Upper Trapizoid
	{ points: [{x: 250, y: 466.666666667}, {x: 450, y: 466.666666667}, { x: 400, y: 583.3333333334}, { x: 300, y: 583.3333333334} ],
	  color: "orange",
	  center: { x: 325, y: 525}
	},
	// Bottom Lower Trapizoid
	{ points: [{ x: 300, y: 583.3333333334}, { x: 400, y: 583.3333333334}, { x: 450, y: 700}, { x: 250, y: 700} ],
	  color: "orange",
	  center: { x: 375, y: 641.666666667}
	},
	// Bottom Hexagon 2
	{ points: [{x: 450, y: 466.666666667}, {x: 550, y: 466.666666667}, { x: 600, y: 583.3333333334}, { x: 550, y: 700}, { x: 450, y: 700}, { x: 400, y: 583.3333333334}  ],
	  color: "red",
	  center: { x: 500, y: 583.3333333334}
	},
];


//This cycles through and draws each shape but looping through the list of points
function drawBoard(board) {

	//Get context	
	if (canvas.getContext) {
    	//var ctx = canvas.getContext('2d');
    	
    	//Loop through ever shape in array passed into board function
		for(var i = 0; i < board.length; i++) {
		
			//These are used to hold the sum of x and y values to average and 
			//calculate the center locations
			var xSum = 0;
			var ySum = 0;
			
			//Start new path at the first points location for shape
    		ctx.beginPath();
    		ctx.moveTo(board[i].points[0].x, board[i].points[0].y);
    		
			for(var j = 1; j < board[i].points.length; j++) {
				
				//Draw a line to the remaining points in the array for the object
				ctx.lineTo(board[i].points[j].x, board[i].points[j].y);

				//Add all the x and y positions for calculating the center
				xSum = xSum + board[i].points[j].x;
				ySum = ySum + board[i].points[j].y;

			}
		
			//Add the first point to the sums which was not added in the loop
			xSum = xSum + board[i].points[0].x;
			ySum = ySum + board[i].points[0].y;

			//Print center location
			console.log("center: { x: " + xSum/board[i].points.length + ", y: " + ySum/board[i].points.length + "}");
		
			//Fill shape with color 
			ctx.fillStyle = shapes[i].color;
    
    		//Fill/draw shape to canvas
			ctx.fill(); 

			//Create new path to draw shape boarder
			ctx.beginPath();
    		ctx.moveTo(board[i].points[0].x, board[i].points[0].y);
    		
    		//Loop through rest of points for boardeer
			for(j = 1; j < board[i].points.length; j++) {
				ctx.lineTo(board[i].points[j].x, board[i].points[j].y);
			}

			//Close shape border
			ctx.lineTo(board[i].points[0].x, board[i].points[0].y);
			
			//Set boarder width
			ctx.lineWidth = 3;
			
			//Draw boareder to canvas
			ctx.stroke();
		
		//ctx.font = '12px serif';
		//ctx.strokeText('Hello world', 10, 50);
		
		//ctx.closePath();
    		//ctx.stroke();
	}
  }
}


//Handle clicks on board
canvas.addEventListener('click', function(event) {
	
	//Get location of click from click event
	//Because canvas is not at page location 0, 0 apply offset
	var x = event.pageX - canvas.offsetLeft;
	var y = event.pageY - canvas.offsetTop;
	
	//Temp distance to hold calculated distance
	var tempDistance;
	
	//Hold the current shortest or best distance
	var bestDistance = 999999999;
	
	//Hold the object with the current shortest distance
	var bestObject;
	
    //Print to log click location on canvas
    console.log(x + ' ' + y);

	//Calcualte shortest distance for all shapes/objects on the screen
	for(var i = 0; i < shapes.length; i++) {
		tempDistance = Math.sqrt(Math.pow((x - shapes[i].center.x), 2) + Math.pow((y - shapes[i].center.y), 2));
		
		//Update the shape with the shortest distance as required
		if(bestDistance > tempDistance) {
			bestDistance = tempDistance;
			bestObject = i;
		}

			
	}

	//Print to console the object location in the array and the color of that object
	console.log("Object:" + bestObject + " Color:" + shapes[bestObject].color);
	
	
			ctx.fillStyle = shapes[bestObject].color;
			ctx.fillRect(100,100,150,75);
		

}, false);


//Call the draw funtion
drawBoard(shapes);

</script>