<html>
<body>
<style type="text/css">
#map{
	/*position: relative;*/
}
.buy_phase_menu{
    display: none;
    position: absolute;
    background-color: white;
    padding: 10px;
    top: 350px;
    left: 550px;
}
.attack_phase_menu{
    display: none;
    position: absolute;
    background-color: white;
    padding: 10px;
    top: 350px;
    left: 550px;
}
</style>
<div id="map">
	<canvas id="canvas" width="700" height="700"></canvas>

	<form class="buy_phase_menu" method="POST" >
		<fieldset>
			<legend>Buy Phase</legend>
			<div>
			  <label>Owner:</label>
			  <text id="buy_phase_owner_name"></text>
			</div>
			<div>
			  <label>Troops:</label>
			  <text id="buy_phase_troop_numbers"></text>
			</div>   
			<p>
				<input type="button" value="Buy" id="buy_phase_buy_button">
				<input type="button" value="Move" id="buy_phase_move_button">  
			</p>
			<p>
				<input type="submit">
				<input id="buy_phase_cancel" type="button" value="Cancel">
			</p>
		</fieldset>
	</form>
	
	<form class="attack_phase_menu" method="POST" >
		<fieldset>
			<legend>Attack Phase</legend>
			<div>
			  <label>Owner:</label>
			  <text id="attack_phase_owner_name"></text>
			</div>
			<div>
			  <label>Troops:</label>
			  <text id="attack_phase_troop_numbers"></text>
			</div>   
			<p>
				<input type="button" value="Attack" id="attack_phase_attack_button">
				<input type="button" value="Move" id="attack_phase_move_button">  
			</p>
			<p>
				<input type="submit">
				<input id="attack_phase_cancel" type="button" value="Cancel">
			</p>
		</fieldset>
	</form>
	
</div>


</body>
</html>
 <script>

 //Establish the Canvas variable
   var canvas = document.getElementById('canvas');
   var ctx = canvas.getContext('2d');

// Shapes vairable used for the functions
// intialized with ajax call
	 var shapes;

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
			ctx.fillStyle = board[i].color;
    
    		//Fill/draw shape to canvas
			ctx.fill(); 

			//Create new path to draw shape boarder
			ctx.beginPath();
    		ctx.moveTo(board[i].points[0].x, board[i].points[0].y);
    		
    		//Loop through rest of points for boarder
			for(j = 1; j < board[i].points.length; j++) {
				ctx.lineTo(board[i].points[j].x, board[i].points[j].y);
			}

			//Close shape border
			ctx.lineTo(board[i].points[0].x, board[i].points[0].y);
			
			//Set boarder width
			ctx.lineWidth = 3;
			
			//Draw boarder to canvas
			ctx.stroke();
			ctx.font = '16px serif';
			ctx.strokeText(board[i].troops, board[i].center.x-7, board[i].center.y);
		
		//ctx.closePath();
    		//ctx.stroke();
	}
  }
}


//Handle clicks on board
canvas.addEventListener('click', function(event) {
	
	var x = document.getElementsByClassName("buy_phase_menu"),
    style = window.getComputedStyle(x[0]),
    display = style.getPropertyValue('display');
    
	if(display === "none"){
		
		// moved the location determining point inside the code for the menu because we don't care where clicks originate from if the menu is visible.
		
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
	
		//Calculate shortest distance for all shapes/objects on the screen
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
	
		var xhttp = new XMLHttpRequest();

		//The function that will be run on state change
		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
    			//Parse the JSON response
    			var response = JSON.parse(this.responseText);
    			console.log(response);
    			if(response.phase == 'buy'){
    				// updating owner information
					var owner = document.getElementById("buy_phase_owner_name");
					owner.innerHTML = shapes[bestObject].color;
					// updating Troop Numbers
					var troops = document.getElementById("buy_phase_troop_numbers");
					troops.innerHTML = shapes[bestObject].troops
					var x = document.getElementsByClassName("buy_phase_menu");
					x[0].style.display = "block";
    			}else {
    				// updating owner information
					var owner = document.getElementById("attack_phase_owner_name");
					owner.innerHTML = shapes[bestObject].color;
					// updating Troop Numbers
					// ensure that this is communicating with other gameplay functionality
					var troops = document.getElementById("attack_phase_troop_numbers");
					troops.innerHTML = shapes[bestObject].troops

					var x = document.getElementsByClassName("attack_phase_menu");
					x[0].style.display = "block";
    			}
    			

    		}
		};
		xhttp.open("GET", "/api/getphase?owner=" + shapes[bestObject].color, true);
		xhttp.send();
		
		
			
	}
	
	
	//ctx.fillStyle = shapes[bestObject].color;
	//ctx.fillRect(100,100,150,75);

}, false);

// The following are the button listeners for the Buy menu.
// They should be invisible until a territory click occurs
// Buy Phase Menu's Cancel button listener
document.getElementById("buy_phase_cancel").addEventListener("click", function(){
    
    //Add: Clear any data fields that haven't been submitted
	
	var x = document.getElementsByClassName("buy_phase_menu");
    x[0].style.display = "none";
	
});

document.getElementById("buy_phase_move_button").addEventListener("click", function(){

	// fill in with move functionality

});

document.getElementById("buy_phase_buy_button").addEventListener("click", function(){

	// fill in with buy functionality 

});

// The following are the button listeners for the Attack menu.
// They should be invisible until a territory click occurs
// Attack Phase Menu's Cancel button listener
document.getElementById("attack_phase_cancel").addEventListener("click", function(){
    
    //Add: Clear any data fields that haven't been submitted
	
	var x = document.getElementsByClassName("attack_phase_menu");
    x[0].style.display = "none";
	
});

document.getElementById("attack_phase_move_button").addEventListener("click", function(){

	// fill in with move functionality

});

document.getElementById("attack_phase_attack_button").addEventListener("click", function(){

	// fill in with Attack functionality 

});

//Ajax request to get map data
var xhttp = new XMLHttpRequest();

  //The function that will be run on state change
  xhttp.onreadystatechange = function() {
  	
  	//What will happen once return is succesful
    if (this.readyState == 4 && this.status == 200) {
    
    //Parse the JSON response
     var response = JSON.parse(this.responseText);
     
     //Call the drawboard function and send the map array in the response
    shapes = response.map;
    drawBoard(shapes);

    }
  };
  xhttp.open("GET", "/api/getmap", true);
  xhttp.send();


//Call the draw funtion
//drawBoard(shapes);

</script>