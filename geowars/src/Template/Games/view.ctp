<html>
<body>
<style type="text/css">
#map{
	/*position: relative;*/
}
.deploy_phase_menu{
    display: none;
    position: absolute;
    background-color: white;
    padding: 10px;
    top: 350px;
    left: 550px;
}
#move_phase_menu{
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
	<canvas id="canvas" width="800" height="700"></canvas>

	<form class="deploy_phase_menu" method="POST" >
		<fieldset>
			<legend>Deploy Phase</legend>
			<div>
			  <label>Owner:</label>
			  <text id="deploy_phase_owner_name"></text>
			</div>
			<div>
			  <label>Troops:</label>
			  <text id="deploy_phase_troop_numbers"></text>
			</div>   
			<p>
				<input type="button" value="Deploy" id="deploy_phase_deploy_button">
				<input type="button" value="Move" id="deploy_phase_move_button">  
			</p>
			<p>
				<input type="submit">
				<input id="deploy_phase_cancel" type="button" value="Cancel">
			</p>
		</fieldset>
	</form>
	
	<form id="move_phase_menu" method="POST" >
		<fieldset>
			<legend>Move Phase</legend>
			<div>
			  <label>Owner:</label>
			  <text id="deploy_phase_owner_name"></text>
			</div>
			<div>
			  <label>Troops:</label>
			  <text id="deploy_phase_troop_numbers"></text>
			</div>   
			<p>
				<input type="button" value="Deploy" id="deploy_phase_deploy_button">
				<input type="button" value="Move" id="deploy_phase_move_button">  
			</p>
			<p>
				<input type="submit">
				<input id="deploy_phase_cancel" type="button" value="Cancel">
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

//Get game id from string
var stringURL = window.location.href;
var gameID = "";

//Will hold to request the page will make
var apiRequest = "";

//Loop to check chars and build gameID string
for(var c = stringURL.length - 1; c > 0; c--) {
	
	//Check is current char is a /
	if(!stringURL[c].localeCompare("/")) {
		
		//Get the Id out of the string with slice
		gameID = stringURL.slice(c + 1, stringURL.length);
		break;
	}
}


// gameInfo holds all information about map that is sent from server in ajax call
var gameInfo;

// Global variable to store what tile ID was clicked. Set to -1 inbetween 
// phases
var tileClicked = -1;

//Array to hold list of territories a user owns popluated on getmap() request
var ownedTerritories = [];


//Function to draw board for showing where to attack and where a use can
//move troops
function drawBorders(territories, phase) {
	var board = gameInfo.map;
	
	//Create new path to draw shape boarder
			
		for(var b = 0; b < territories.length; b++) {
			ctx.beginPath();
    		ctx.moveTo(board[territories[b]].points[0].x, board[territories[b]].points[0].y);
    		
    		//Loop through rest of points for boarder
			for(var a = 1; a < board[territories[b]].points.length; a++) {
				ctx.lineTo(board[territories[b]].points[a].x, board[territories[b]].points[a].y);
			}

			//Close shape border
			ctx.lineTo(board[territories[b]].points[0].x, board[territories[b]].points[0].y);
			
			//Set boarder width
			ctx.lineWidth = 3;
			
			//Set Color
			if(phase == 1) {
				ctx.strokeStyle = "red";
			} else if(phase == 2) {
				ctx.strokeStyle = "lime";
			} else if(phase == 3) {
				ctx.strokeStyle = "black";
			}
			
			//Draw boarder to canvas
			ctx.stroke();
		}
}

//This cycles through and draws each shape but looping through the list of points
function drawBoard() {
	var board = gameInfo.map;
	//Get context	
	if (canvas.getContext) {
    	
    	//Loop through ever shape in array passed into board function
		for(var i = 0; i < board.length; i++) {
		
			//These are used to hold the sum of x and y values to average and 
			//calculate the center locations
			var xSum = 0;
			var ySum = 0;
			
			//Start new path at the first points location for shape
    		ctx.beginPath();
    		ctx.strokeStyle = "black";
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
			//console.log("center: { x: " + xSum/board[i].points.length + ", y: " + ySum/board[i].points.length + "}");
		
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

		}
		
		ctx.font = 'normal 16px/1 "Segoe UI",Arial';
		ctx.fillStyle = '#888888';
		ctx.strokeText('Current Phase: ' + gameInfo.phase, 650, 50 );
		ctx.strokeText('Current Turn: ' + gameInfo.currentTurn, 650, 70 );
		ctx.strokeText('Available Troops: ' + gameInfo.troopsAvailable, 650, 90 );
		
		//Give user instrucitons on what to do after board is drawn
		
/////////////Deploy Phase/////////////////
		
		//User has no troops to deploy
		if(!gameInfo.phase.localeCompare('deploy') && gameInfo.troopsAvailable < 1) {
			
			alert("You have no troops to deploy, once all users have completed their" + 
			" deployments the game will move on to the next phase.");
		
		//User has troops to deploy
		} else if(!gameInfo.phase.localeCompare("deploy")) {
			//Alert to let user know what to do
			alert("This is the deployment phase, select a territory to deploy " +
			 "your troops. You have " + gameInfo.troopsAvailable + " troops available " +
			 "to deply");
			
			//Indicate which territores the user can deploy to
			drawBorders(ownedTerritories, 2);
		}
		
/////////////Move Phase/////////////////
		if(!gameInfo.phase.localeCompare("move")) {
			alert("This is the move phase, select a territory to move troops" + 
			" from. Then you will choose how many troops to move and where to move them.");
			
			//Indicate which territores the user can move troops from
			drawBorders(ownedTerritories, 2);
		}
	}
}


//Handle clicks on board
canvas.addEventListener('click', function(event) {
	
	var x = document.getElementsByClassName("deploy_phase_menu"),
    style = window.getComputedStyle(x[0]),
    display = style.getPropertyValue('display');
    var map = gameInfo.map;
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
		for(var i = 0; i < map.length; i++) {
			tempDistance = Math.sqrt(Math.pow((x - map[i].center.x), 2) + Math.pow((y - map[i].center.y), 2));
			
			//Update the shape with the shortest distance as required
			if(bestDistance > tempDistance) {
				bestDistance = tempDistance;
				bestObject = i;
			}
	
				
		}
	
		//Print to console the object location in the array and the color of that object
		console.log("Object:" + bestObject + " Color:" + map[bestObject].color);
	
		//Set tileclicked (global var for page) to the best object clicked
		tileClicked = bestObject;
		
		//////////////////Deploy Phase///////////////////////
		if(!gameInfo.phase.localeCompare('deploy') && gameInfo.troopsAvailable < 1) {
		
		} else if(!gameInfo.phase.localeCompare('deploy')) {
			
			//User does not own this territory
			if(map[tileClicked].owner != gameInfo.userID) {
				alert("You do not own this " + map[tileClicked].shape +
				", please select another territory.");
				return;
			}
		
			if (confirm("You want to delpoy your troops to " + map[tileClicked].shape
				+ ".") == true) {
    			apiRequest = "/api/postdeploy/" + gameInfo.gameID + "/" +
    			gameInfo.userID + "/" + tileClicked + "/" + gameInfo.troopsAvailable;
			} else {
    			return;
			}
		}
		
		//////////////////Move Phase///////////////////////
		if(!gameInfo.phase.localeCompare('move')) {
			
			//User does not own this territory
			if(map[tileClicked].owner != gameInfo.userID) {
				alert("You do not own this " + map[tileClicked].shape +
				", please select another territory.");
				return;
			}
			
			var moveWindow = document.getElementById("move_phase_menu")
			moveWindow.style.display = "block";
			
		}
		
		//Use Funtion to draw new boarders
		//Draw different borders depending on game phase
		
		//drawBorders(gameInfo.map[tileClicked].adjacentTerritories, 1);
		
		
		//Removing this for now
		
		var xhttp = new XMLHttpRequest();

		//The function that will be run on state change
		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
    			//Parse the JSON response
    			//var response = JSON.parse(this.responseText);
    			
    			/*
    			if(response.phase == 'deploy'){
    			
    				// updating owner information
					var owner = document.getElementById("deploy_phase_owner_name");
					owner.innerHTML = map[bestObject].color;
					// updating Troop Numbers
					var troops = document.getElementById("deploy_phase_troop_numbers");
					troops.innerHTML = map[bestObject].troops
					var x = document.getElementsByClassName("deploy_phase_menu");
					x[0].style.display = "block";
    			}else {
    				// updating owner information
					var owner = document.getElementById("attack_phase_owner_name");
					owner.innerHTML = map[bestObject].color;
					// updating Troop Numbers
					// ensure that this is communicating with other gameplay functionality
					var troops = document.getElementById("attack_phase_troop_numbers");
					troops.innerHTML = map[bestObject].troops

					var x = document.getElementsByClassName("attack_phase_menu");
					x[0].style.display = "block";
    			} */
    			

    		}
		};
		xhttp.open("GET", apiRequest, true);
		xhttp.send(); 
		
		
			
	}

}, false);

// The following are the button listeners for the Deploy menu.
// They should be invisible until a territory click occurs
// Deploy Phase Menu's Cancel button listener
document.getElementById("deploy_phase_cancel").addEventListener("click", function(){
    
    //Add: Clear any data fields that haven't been submitted
	
	var x = document.getElementsByClassName("deploy_phase_menu");
    x[0].style.display = "none";
    drawBorders(gameInfo.map[tileClicked].adjacentTerritories, 3);
	
});

document.getElementById("deploy_phase_move_button").addEventListener("click", function(){

	// fill in with move functionality

});

document.getElementById("deploy_phase_deploy_button").addEventListener("click", function(){
	if(tileClicked!=-1){
		var xhttp = new XMLHttpRequest();

		//The function that will be run on state change
		xhttp.onreadystatechange = function() {
  	
  			//What will happen once return is succesful
    		if (this.readyState == 4 && this.status == 200) {
    			//Parse the JSON response
    			var response = this.responseText;
				refreshBoard();
				var x = document.getElementsByClassName("deploy_phase_menu");
    			x[0].style.display = "none";
    		}
		};
		var urlString = "/api/postdeploy/" + gameInfo.gameID + "/";
		urlString += gameInfo.userID + "/" + tileClicked + "/5";
		xhttp.open("POST",  urlString , true);
		xhttp.send();	
	}


});

// The following are the button listeners for the Attack menu.
// They should be invisible until a territory click occurs
// Attack Phase Menu's Cancel button listener
document.getElementById("attack_phase_cancel").addEventListener("click", function(){
    
    //Add: Clear any data fields that haven't been submitted
	
	var x = document.getElementsByClassName("attack_phase_menu");
    x[0].style.display = "none";
    drawBorders(gameInfo.map[tileClicked].adjacentTerritories, 3);
	
});

document.getElementById("attack_phase_move_button").addEventListener("click", function(){

	// fill in with move functionality

});

document.getElementById("attack_phase_attack_button").addEventListener("click", function(){

	// fill in with Attack functionality 

});

//Ajax request to get map data
function refreshBoard(){
	var xhttp = new XMLHttpRequest();

	//The function that will be run on state change
	xhttp.onreadystatechange = function() {
  	
  		//What will happen once return is succesful
    	if (this.readyState == 4 && this.status == 200) {
    
    	//Parse the JSON response
    	var response = JSON.parse(this.responseText);
     
    	//Call the drawboard function and send the map array in the response
    	gameInfo = response.game;
    	
    	
		//Create a list of territories a player owns
		for(var i = 0; i < gameInfo.map.length; i++){
			if(gameInfo.map[i].owner == gameInfo.userID){
				ownedTerritories.push(i);
			}
		}
    
    	drawBoard();

    	}
	};
	xhttp.open("GET", "/api/getmap/" + gameID, true);
	xhttp.send();
}

refreshBoard();


</script>