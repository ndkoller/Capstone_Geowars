<p>Find a game to join.</p>
<?= $this->Flash->render() ?>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead id="find_table_head">
            <tr>
                <th>Game ID</th>
                <th>Game Name</th>
                <th>Created By</th>
                <th>Joined Players</th>
                <th>Minimum Human Players</th>
                <th>Maximum Players</th>
                <!-- <th>Planning Phase Time (Min)</th>
                <th>Attack Phase Time (Min)</th>
                <th>Start Timer</th> -->
                <th>Bots</th>
                <th>Bot Difficulty</th>
               <!-- <th>Map</th> -->
                <th>Join</th>
            </tr>
        </thead>
    <tbody id="find_table_body">
        
    </tbody>
    </table>
</div>

<script type="text/javascript">
//https://stackoverflow.com/questions/847185/convert-a-unix-timestamp-to-time-in-javascript
// Will be utilized in future updates that rely on start game timing.
function timeConverter(UNIX_timestamp){
  var a = new Date(UNIX_timestamp * 1000);
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var year = a.getFullYear();
  var month = months[a.getMonth()];
  var date = a.getDate();
  var hour = a.getHours();
  var min = a.getMinutes();
  var sec = a.getSeconds();
  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
  return time;
}

var gameList;
//Ajax request to get Find Data
var xhttp = new XMLHttpRequest();

  //The function that will be run on state change
  xhttp.onload = function() {
  	
  	//What will happen once return is succesful
    if (this.readyState == 4 && this.status == 200) {

        //Parse the JSON response
        var response = JSON.parse(this.responseText);
         
         //Call the drawboard function and send the map array in the response
        gameList = response.games;
        console.log(gameList);
        var gameA;
        var i;
        var tableBody = document.getElementById('find_table_body');
        for (i = 0; i < gameList.length; i++) {
            var tr = document.createElement('TR');
            
            var gameID = document.createElement('TD');
            var game_name = document.createElement('TD');
            var CreatedBy = document.createElement('TD');
            var JoinedPlayers = document.createElement('TD');
            var MinPlayers = document.createElement('TD');
            var MaxPlayers = document.createElement('TD');
            var PlanningPhase = document.createElement('TD');
            var AttackPhase = document.createElement('TD');
            var StartTime = document.createElement('TD');
            var Bots = document.createElement('TD');
            var Difficulty = document.createElement('TD');
            var Map = document.createElement('TD');
            var joinButton = document.createElement('TD');

            gameID.appendChild(document.createTextNode(gameList[i].id));
            tr.appendChild(gameID);
            
            game_name.appendChild(document.createTextNode(gameList[i].game_name));
            tr.appendChild(game_name);
            
            CreatedBy.appendChild(document.createTextNode(gameList[i].created_by));
            tr.appendChild(CreatedBy);
            
            JoinedPlayers.appendChild(document.createTextNode(gameList[i].currentPlayers)); // replace with the count of the userID's from the query 'JOIN game_users with game on gameID'
            tr.appendChild(JoinedPlayers);
            
            MinPlayers.appendChild(document.createTextNode(gameList[i].min_users));
            tr.appendChild(MinPlayers);
            
            MaxPlayers.appendChild(document.createTextNode(gameList[i].max_users));
            tr.appendChild(MaxPlayers);
            /*
            PlanningPhase.appendChild(document.createTextNode(gameList[i].phase_one_duration));
            tr.appendChild(PlanningPhase);
            
            AttackPhase.appendChild(document.createTextNode(gameList[i].phase_two_duration));
            tr.appendChild(AttackPhase);
            
            var formattedTime = timeConverter(gameList[i].start_time);
            StartTime.appendChild(document.createTextNode(formattedTime));
            tr.appendChild(StartTime);
            */
            // Bots Conversion logic
            if(gameList[i].atStart_opt === 2 || gameList[i].atStart_opt === 3){
                Bots.appendChild(document.createTextNode('Yes'));
            }
            else
            {
                Bots.appendChild(document.createTextNode('No'));
            }
            tr.appendChild(Bots);
            
           /* 
            Map.appendChild(document.createTextNode(gameList[i].map));
            tr.appendChild(Map);
            */
            if(gameList[i].bot_hard_mode == 1){
                Difficulty.appendChild(document.createTextNode("Hard"));
            }
            else{
                Difficulty.appendChild(document.createTextNode("Easy"));
            }
            
            tr.appendChild(Difficulty);
            
            var btn = document.createElement("BUTTON");        // Create a <button> element
            var t = document.createTextNode("Join");       // Create a text node
            btn.appendChild(t);                                // Append the text to <button>
            btn.addEventListener("click",function(){ join(this);}, false);
            btn.setAttribute('id', 'join' + gameList[i].id);
            joinButton.appendChild(btn);
            tr.appendChild(joinButton);

            tableBody.appendChild(tr);
        }
    }
    function join(joinID){
        var ID = joinID.parentNode.parentNode.cells[0].innerHTML;
        console.log(ID);
        
        var joinButton = document.getElementById('join' + ID);
        joinButton.disabled = true;
        var ajaxreq = new XMLHttpRequest();
        ajaxreq.onload = function() {
              if (ajaxreq.readyState == 4 && ajaxreq.status === 200) {
                var responseObject = JSON.parse(ajaxreq.responseText);
                if (responseObject.results == 1) {
                  //Todo
                  window.location.replace('/games/mygames');
                    
                } else {
                  //Todo
                  window.location.replace('/games/find');
                }
                
              }
        };
        
        //Values to post
        var postString = 'game_id=' + ID;
        ajaxreq.open('POST', '/games/join', true);
        ajaxreq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        ajaxreq.send(postString);
    }
  };
  xhttp.open("GET", "/games/findall", true);
  xhttp.send();



</script>