<p>Create a new game.</p>
<?= $this->Flash->render() ?>

<form class="form-horizontal">
<!--
  <div class="form-group">
    <label for="map" class="col-sm-2 control-label">Map</label>
    <div class="col-sm-6">
      <select class="form-control" id="map">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <label for="planningPhase" class="col-sm-2 control-label">Planning Phase (minutes)</label>
    <div class="col-sm-6">
      <input type="number" min="1" class="form-control" id="planningPhase" placeholder="1" required="true">
    </div>
  </div>
  
  <div class="form-group">
    <label for="attackPhase" class="col-sm-2 control-label">Attack Phase (minutes)</label>
    <div class="col-sm-6">
      <input type="number" min="1" class="form-control" id="attackPhase" placeholder="1" required="true">
    </div>
  </div>
  -->
    <div class="form-group">
    <label for="name" class="col-sm-2 control-label">Game Name</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="name" placeholder="ex. Shape War">
    </div>
  </div>
  <div class="form-group">
  <label for="minPlayers" class="col-sm-2 control-label">Minimum Human Players</label>
  <div class="col-sm-6">
      <select class="form-control" id="minPlayers">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
      </select>
    </div>
  </div>
  
  <div class="form-group">
  <label for="maxPlayers" class="col-sm-2 control-label">Max Players</label>
  <div class="col-sm-6">
      <select class="form-control" id="maxPlayers">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
      </select>
    </div>
  </div>
<!--  
  <div class="form-group">
    <label for="date" class="col-sm-2 control-label">Start Date</label>
    <div class="col-sm-6">
      <input type="date" class="form-control" id="date" required="true">
    </div>
  </div>
  
  <div class="form-group">
    <label for="time" class="col-sm-2 control-label">Start Time</label>
    <div class="col-sm-6">
      <input type="time" class="form-control" id="time" required="true">
    </div>
  </div>

  <div class="form-group">
  <label for="atStart" class="col-sm-2 control-label">At start time</label>
  <div class="col-sm-6">
      <select class="form-control" id="atStart">
         <option value="1">Cancel Game</option> 
          <option value="2">Add Bots to reach minimum players</option>
          <option value="3">Add Bots to reach maximum players</option>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <label  class="col-sm-2 control-label"></label>
    <div class="col-sm-6">
      <p>This action will take place if minimum player haven't been reached
      by start time.</p>
    </div>
  </div>
  -->
   <div class="form-group">
  <label for="botDifficulty" class="col-sm-2 control-label">Bot Difficulty</label>
    <div class="col-sm-6">
          <input type="radio" id="r1" name="botDifficulty" value="easy"> Easy<br>
          <input type="radio" id="r2" name="botDifficulty" value="hard"> Hard<br>
    </div>
  </div>
  <div class="form-group">
    <label  class="col-sm-2 control-label"></label>
    <div class="col-sm-6 checkbox">
      <label>
        <input id="join" type="checkbox" value="join">
        Join Game on creation
      </label>
    </div>
  </div>
  
  <div class="form-group">
    
  </div>
</form>

<div class="col-sm-offset-2 col-sm-10">
      <button onclick="makeGame()" class="btn btn-default">Make Game</button>
    </div>

<script type="text/javascript">

function randomIntFromInterval(min,max)
{
    return Math.floor(Math.random()*(max-min+1)+min);
}

function makeGame() {

    // stubbing in fixed data for testing a limited version of the game.

    //var map = document.getElementById('map').value;
    //var planningPhase = document.getElementById('planningPhase').value;
    //var attackPhase = document.getElementById('attackPhase').value;
    var map = 1;
    var game_name = document.getElementById('name').value;
    if(game_name == ""){
      game_name = 'No Name ' + randomIntFromInterval(1,100);
    }
    var planningPhase = 1;
    var attackPhase = 1;
    var minPlayers = document.getElementById('minPlayers').value;
    var maxPlayers = document.getElementById('maxPlayers').value;
    //var startDate = document.getElementById('date').value;
    //var startTime = document.getElementById('time').value;
    //var atStart = document.getElementById('atStart').value;
    var atStart = 2;
    var botDifficulty;
    // defaults to easy if no selction made
    if (document.getElementById('r2').checked) {
       botDifficulty = document.getElementById('r2').value;
    }
    else
    {
      botDifficulty = "easy";
    }
    var join = document.getElementById('join').value;
    
    //Convert entered time to unix time to sent to server
    //var unixTime = new Date(startDate + " " + startTime);
    var unixTime = new Date();
    
    
    /*Data Validation*/
    /*if (!startDate || !startTime){
      unixTime = new Date();
    }
    if(!planningPhase){
      // required Planning Time
      planningPhase = 1; // Default 1 minute
    }
    if(!attackPhase){
      // Required Attack Time 
      attackPhase = 1; // Default 1 minute
    }
    */
    var ajaxreq = new XMLHttpRequest();
    ajaxreq.onload = function() {
          if (ajaxreq.readyState == 4 && ajaxreq.status === 200) {
            
            var responseObject = JSON.parse(ajaxreq.responseText);
            if (responseObject.results == 1) {
              //Todo
              window.location.replace('/games/mygames');
            } else {
              //Todo
              window.location.replace('/games/create');
            }
            
          }
    };
    
    //Values to post
    //The start day and time have been combined and posted as startUNIXTime
    var postString = 'map=' + map + '&planningPhase=' + planningPhase + '&attackPhase=' + attackPhase
        + '&minPlayers=' + minPlayers + '&maxPlayers=' + maxPlayers + '&startUNIXTime=' + unixTime.getTime()  
        +  "&atStart=" + atStart + '&join=' + join + '&botDifficulty=' + botDifficulty + '&game_name=' + game_name;
    ajaxreq.open('POST', '/games/createprocess', true);
    ajaxreq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    ajaxreq.send(postString);

}


</script>