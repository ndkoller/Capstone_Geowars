<p>Find a game to join.</p>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead id="find_table_head">
            <tr>
                <th>Game ID</th>
                <th>Created By</th>
                <th>Joined Players</th>
                <th>Minimum Players</th>
                <th>Maximum Players</th>
                <th>Planning Phase Time (Min)</th>
                <th>Attack Phase Time (Min)</th>
                <th>Start Timer</th>
                <th>Bots</th>
                <th>Map</th>
                <th>Join</th>
            </tr>
        </thead>

    </table>
</div>

<script type="text/javascript">
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
        
        var tableBody = document.createElement('TBODY')
            for (var gameA in gameList) {
                var tr = document.createElement('TR');
                
                for (var data in gameA) {
                    var td = document.createElement('TD')
                    td.appendChild(document.createTextNode(data));
                    tr.appendChild(td);
                }
                tableBody.appendChild(tr);
            }
            var tableHead = document.getElementById("find_table_head");
            tableHead.appendChild(tableBody);
    }
  };
  xhttp.open("GET", "/games/findall", true);
  xhttp.send();



</script>