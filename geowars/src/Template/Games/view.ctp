<html>
<body>

<style type="text/css">
#map{
	/*position: relative;*/
}

#move_phase_menu{
    display: none;
    position: absolute;
    background-color: white;
    padding: 10px;
    top: 350px;
    left: 475px;
    width: 250px;
}
#attack_phase_menu{
    display: none;
    position: absolute;
    background-color: white;
    padding: 10px;
    top: 350px;
    left: 475px;
    width: 250px;
}

</style>
<div id="map">
	<canvas id="canvas" width="800" height="700"></canvas>

	<div id="move_phase_menu" >
		<fieldset>
			<legend>Move Phase</legend>
			<div>
			  <label>Move From:</label>
			  <text id="move_from"></text>
			</div>
			<div>
			  <label>Move To:</label>
			  <text id="move_to"></text>
			</div>
			<div>
			  <label>Troops to Move:</label>
			  <input type="number" id="to_move">
			</div>
			<div>
			  <label id=>*You can move x troops.</label>
			</div>
			<br>
			<div>
				<button type="button" id="move_cancel">Cancel</button>
				<button type="button" id="move_to_button">Move To</button>
				<button type="button" id="move_submit">Submit</button>
			</div>
		</fieldset>
		
	</div>
	
	<div id="attack_phase_menu" >
		<fieldset>
			<legend>Attack Phase</legend>
			<div>
			  <label>Attack From:</label>
			  <text id="attack_from"></text>
			</div>
			<div>
			  <label>Attack:</label>
			  <text id="attack_to"></text>
			</div>
			<div>
			  <label>Troops to Attack:</label>
			  <input type="number" id="to_attack">
			</div>
			<div>
			  <label id=>*You can attack with x troops.</label>
			</div>
			<br>
			<div>
				<button type="button" id="attack_cancel">Cancel</button>
				<button type="button" id="attack_to_button">Attack To</button>
				<button type="button" id="attack_submit">Submit</button>
			</div>
		</fieldset>
		
	</div>
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

//Assign id to vars
var moveWindow = document.getElementById("move_phase_menu");
var moveTroopsTo = document.getElementById("move_to");
var moveTroopsFrom = document.getElementById("move_from");
var moveTroopsNumber = document.getElementById("to_move");
var moveCancelButton = document.getElementById('move_cancel');
var moveToButton = document.getElementById('move_to_button');
var moveSubmitButton = document.getElementById('move_submit');

var attackWindow = document.getElementById("attack_phase_menu");
var attackTroopsTo = document.getElementById("attack_to");
var attackTroopsFrom = document.getElementById("attack_from");
var attackTroopsNumber = document.getElementById("to_attack");
var attackCancelButton = document.getElementById('attack_cancel');
var attackToButton = document.getElementById('attack_to_button');
var attackSubmitButton = document.getElementById('attack_submit');

//Loop to check chars and build gameID string
for(var c = stringURL.length - 1; c > 0; c--) {
	
	//Check is current char is a /
	if(!stringURL[c].localeCompare("/")) {
		
		//Get the Id out of the string with slice
		gameID = stringURL.slice(c + 1, stringURL.length);
		break;
	}
}

var canAttack = [];

// gameInfo holds all information about map that is sent from server in ajax call
var gameInfo;

// Global variable to store what tile ID was clicked. Set to -1 inbetween 
// phases
var tileClicked = -1;

//Array to hold list of territories a user owns popluated on getmap() request
var ownedTerritories = [];

var territoryFrom = -1;
var territoryTo = -1;


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
			} else if(phase == 4) {
				ctx.strokeStyle = "aqua";
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
		
/////////////Attack Phase/////////////////
		if(!gameInfo.phase.localeCompare("attack")) {
			alert("This is the attack phase, select a territory to attack " + 
			" from. Then you will choose where to attack and with how many troops.");
			
			//Indicate which territores the user can attack from
			drawBorders(ownedTerritories, 2);
		}
	}
}


//Handle clicks on board
canvas.addEventListener('click', function(event) {
	
		//Create map variable
		var map = gameInfo.map;
		
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
    			
    			//AJAX Request
				var xhttp = new XMLHttpRequest();

				//The function that will be run on state change
				xhttp.onreadystatechange = function() {
    				if (this.readyState == 4 && this.status == 200) {
						//Add here if needed
						refreshBoard();
    				}
				};
				
				xhttp.open("GET", apiRequest, true);
				xhttp.send();
				
			} else {
    			return;
			}
		}
		
		//////////////////Move Phase///////////////////////
		if(!gameInfo.phase.localeCompare('move')) {
			
			if(territoryFrom == -1) {
				//User does not own this territory
				if(map[tileClicked].owner != gameInfo.userID) {
					alert("You do not own this " + map[tileClicked].shape +
					", please select another territory.");
					return;
				}
			
				territoryFrom = tileClicked;
				drawBorders([territoryFrom], 4);
				moveTroopsFrom.textContent = gameInfo.map[territoryFrom].shape;
				moveTroopsTo.textContent = "";
				moveSubmitButton.disabled = true;
				moveToButton.disabled = false;
				moveWindow.style.display = "block";
			} else {
				territoryTo = tileClicked;
				drawBorders([territoryTo], 4);
				moveTroopsFrom.textContent = gameInfo.map[territoryFrom].shape;
				moveTroopsTo.textContent = gameInfo.map[territoryTo].shape;
				moveSubmitButton.disabled = false;
				moveToButton.disabled = true;
				moveWindow.style.display = "block";
			}
			
		}
		
		//////////////////Attack Phase///////////////////////
		if(!gameInfo.phase.localeCompare('attack')) {
			
			if(territoryFrom == -1) {
				//User does not own this territory
				if(map[tileClicked].owner != gameInfo.userID) {
					alert("You do not own this " + map[tileClicked].shape +
					", please select another territory.");
					return;
				}
				
				//Ensure canAttack has no items in it
				canAttack = [];
				
				//Add Value to canAttack
				for(var r = 0; r < gameInfo.map[tileClicked].adjacentTerritories.length; r++) {
					if(gameInfo.map[gameInfo.map[tileClicked].adjacentTerritories[r]].owner != gameInfo.userID) {
						canAttack.push(gameInfo.map[tileClicked].adjacentTerritories[r]);
					}
				}	
				
				//If no location can be attacked notify user
				if(canAttack.length == 0) {
					alert("This territoy does not have any adjacent" +
					"territories you can attack. Please choose a different territory");
					return;
				}
				
				territoryFrom = tileClicked;
				
				//All owned territories go black
				drawBorders(ownedTerritories, 3);
				
				//Adjacent turn red
				drawBorders(canAttack, 1);
				attackTroopsFrom.textContent = gameInfo.map[territoryFrom].shape;
				attackTroopsTo.textContent = "";
				attackSubmitButton.disabled = true;
				attackToButton.disabled = false;
				attackWindow.style.display = "block";
			} else {
				territoryTo = tileClicked;
				drawBorders(canAttack, 3);
				drawBorders([territoryTo], 1);
				attackTroopsFrom.textContent = gameInfo.map[territoryFrom].shape;
				attackTroopsTo.textContent = gameInfo.map[territoryTo].shape;
				attackSubmitButton.disabled = false;
				attackToButton.disabled = true;
				attackWindow.style.display = "block";
			}
			
		}
	
		
			
	

}, false);

// The following are the button listeners for the Deploy menu.
// They should be invisible until a territory click occurs
// Deploy Phase Menu's Cancel button listener


////////////////Move Menu Buttons ///////////////////////
moveCancelButton.addEventListener("click", function(){

	if(territoryFrom > -1) {
		drawBorders([territoryFrom], 2);
		territoryFrom = -1;
	}
	
	if(territoryTo > -1) {
		drawBorders([territoryTo], 2);
		territoryTo = -1;
		
	}		
	
	moveWindow.style.display = "none";

});

moveToButton.addEventListener("click", function(){

	moveWindow.style.display = "none";

});

moveSubmitButton.addEventListener("click", function(){
	
	//Check if user is trying to move to many troops
	if(parseInt(moveTroopsNumber.value) > gameInfo.map[territoryFrom].troops - 1) {
		alert("You have tried to move more troops out of this territory than you are allowed." + 
			" Please select less than" + (gameInfo.map[territoryFrom].troops - 1) +" troops to move.");
		return;
	}
	
	//Submit to API
	var apiRequest = "/api/postmove/"  + gameInfo.gameID + "/" +
    			gameInfo.userID + "/" + territoryFrom + "/" + territoryTo
    			+ "/" + parseInt(moveTroopsNumber.value);
    			
    //AJAX Request
	var xhttp = new XMLHttpRequest();

	//The function that will be run on state change
	xhttp.onreadystatechange = function() {
    	if (this.readyState == 4 && this.status == 200) {
			//Add here if needed
			refreshBoard();
    	}
	};
	xhttp.open("GET", apiRequest, true);
	xhttp.send();
	
	//Clean up game and hide menu
	drawBorders([territoryFrom], 2);
	territoryFrom = -1;
	drawBorders([territoryTo], 2);
	territoryTo = -1;
	moveWindow.style.display = "none";

});

////////////////Attack Menu Buttons ///////////////////////
attackCancelButton.addEventListener("click", function(){

	//Adjacent turn black
	drawBorders(canAttack, 3);
	
	//All owned territories go green
	drawBorders(ownedTerritories, 2);
		
	territoryFrom = -1;
	territoryTo = -1;
		
	canAttack = [];

	attackWindow.style.display = "none";

});

attackToButton.addEventListener("click", function(){

	attackWindow.style.display = "none";

});

attackSubmitButton.addEventListener("click", function(){
	
	//Check if user is trying to move to many troops
	if(parseInt(attackTroopsNumber.value) > gameInfo.map[territoryFrom].troops - 1) {
		alert("You have tried to move more troops out of this territory than you are allowed." + 
			" Please select less than " + (gameInfo.map[territoryFrom].troops - 1) +" troops to move.");
		return;
	}
	
	//Submit to API
	var apiRequest = "/api/postattack/"  + gameInfo.gameID + "/" +
    			gameInfo.userID + "/" + territoryFrom + "/" + territoryTo
    			+ "/" + parseInt(moveTroopsNumber.value);
    			
    //AJAX Request
	var xhttp = new XMLHttpRequest();

	//The function that will be run on state change
	xhttp.onreadystatechange = function() {
    	if (this.readyState == 4 && this.status == 200) {
			//Add here if needed
			refreshBoard();
    	}
	};
	xhttp.open("GET", apiRequest, true);
	xhttp.send();
	
	//Clean up game and hide menu
	//Adjacent turn black
	drawBorders(canAttack, 3);
	
	//All owned territories go green
	drawBorders(ownedTerritories, 2);
		
	territoryFrom = -1;
	territoryTo = -1;
		
	canAttack = [];
	
	moveWindow.style.display = "none";

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