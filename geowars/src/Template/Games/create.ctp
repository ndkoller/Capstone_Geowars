<p>Create a new game.</p>

<form class="form-horizontal">
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
      <input type="number" class="form-control" id="planningPhase">
    </div>
  </div>
  
  <div class="form-group">
    <label for="attackPhase" class="col-sm-2 control-label">Attack Phase (minutes)</label>
    <div class="col-sm-6">
      <input type="number" class="form-control" id="attackPhase">
    </div>
  </div>
  
  <div class="form-group">
  <label for="minPlayers" class="col-sm-2 control-label">Minimum Players</label>
  <div class="col-sm-6">
      <select class="form-control" id="minPlayers">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
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
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <label for="date" class="col-sm-2 control-label">Start Date</label>
    <div class="col-sm-6">
      <input type="date" class="form-control" id="date">
    </div>
  </div>
  
  <div class="form-group">
    <label for="time" class="col-sm-2 control-label">Start Time</label>
    <div class="col-sm-6">
      <input type="time" class="form-control" id="time">
    </div>
  </div>
  
  <div class="form-group">
  <label for="atStart" class="col-sm-2 control-label">At start time</label>
  <div class="col-sm-6">
      <select class="form-control" id="maxPlayers">
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
  
  <div class="form-group">
    <label  class="col-sm-2 control-label"></label>
    <div class="col-sm-6 checkbox">
      <label>
        <input type="checkbox" value="">
        Join Game on creation
      </label>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Make Game</button>
    </div>
  </div>
</form>