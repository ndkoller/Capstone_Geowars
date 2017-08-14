<?php
namespace App\Controller;
use App\Controller\AppController;
use App\Model\Entity\User;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
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
        $this->Auth->allow(array('test', 'checkGameState', 'getMap', 'postAttack', 'postDeploy','postMove', 'getPhase'));
    }
    
    public function getPhase($gameId) {
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $gameId])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        $this->set('phase', $gameInfo->current_phase);
    }
    
    // test endpoint to just test whatever.
    public function test(){
        $this->viewBuilder()->layout('ajax');
        $this->updateGamePhase(26);
        $output = 'stuff';
        $this->set('result', $output);
    }
    
    public function checkGameState($gameId, $lastReportedPhase, $currentTurn) {
        $this->viewBuilder()->layout('ajax');
        
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $gameId])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        $shouldReload = false;
        
        if($gameInfo->current_phase != $lastReportedPhase) {
            $shouldReload = true;
        }
        
        if($currentTurn != $gameInfo->last_completed_turn + 1){
            $shouldReload = true;
        }
        
        if($shouldReload){
            $this->set('result', 'reload');
        } else {
            $this->set('result', 'waiting');
        }
    }
    
    
    public function postDeploy($gameId, $userId,$tileId, $numTroops){
        $this->viewBuilder()->layout('ajax');
        $saved = $this->saveNewDeploy($gameId, $userId, $tileId, $numTroops);
            
        if($saved){
            if ($this->activePlayersCompletedPhase($gameId, 'deploy')){
                // Everything is done with real players. Process AI deploys,
                // modify territories and update the phase
                $this->processAiDeployment($gameId);
                $this->updateTerritoryAfterDeployment($gameId);
                $this->updateGamePhase($gameId);
                $this->set('result', "success");
            }
        }else{
            $this->set('result', "failure");
        }
    }
    
    public function saveNewDeploy($gameId,$userId,$tileId,$numTroops){
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $gameId])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        $this->loadModel('DeploymentActions');
        $newDeploymentAction = $this->DeploymentActions->newEntity();
        $newDeploymentAction->game_id = $gameId;
        $newDeploymentAction->game_user_id = $userId;
        $newDeploymentAction->turn_number = $gameInfo->last_completed_turn + 1;
        $newDeploymentAction->num_troops = $numTroops;
        $newDeploymentAction->to_territory_id = $tileId;
        return $this->DeploymentActions->save($newDeploymentAction);
    }
    
    public function postMove($gameId, $userId,$fromTileId, $toTileId, $numTroops){
        $this->viewBuilder()->layout('ajax');
        $saved = $this->saveNewMove($gameId, $userId, $fromTileId, $toTileId, $numTroops);
            
        if($saved){
            if ($this->activePlayersCompletedPhase($gameId, 'move')){
                $this->processAiMove($gameId);
                $this->updateTerritoryAfterMove($gameId);
                $this->updateGamePhase($gameId);
                $this->set('result', "success");
            }
        }else{
            $this->set('result', "failure");
        }
    }

    public function saveNewMove($gameId, $userId, $fromTileId, $toTileId, $numTroops) {
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $gameId])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        $this->loadModel('MoveActions');
        $newMove = $this->MoveActions->newEntity();
        $newMove->game_id = $gameId;
        $newMove->game_user_id = $userId;
        $newMove->turn_number = $gameInfo->last_completed_turn + 1;
        $newMove->num_troops = $numTroops;
        $newMove->from_territory_id = $fromTileId;
        $newMove->to_territory_id = $toTileId;
        return $this->MoveActions->save($newMove);
    }
    
    public function postAttack($gameId,$userId, $fromTileId, $toTileId, $numTroops) {
        
        echo $gameId;
        
        $this->viewBuilder()->layout('ajax');
        $saved = $this->saveNewAttack($gameId,$userId, $fromTileId, $toTileId, $numTroops);
        
        echo $saved;
        
        if($saved){
            if ($this->activePlayersCompletedPhase($gameId, 'attack')){
                $this->processAiAttack($gameId);
                $this->updateTerritoryAfterAttack($gameId);
                $this->updateGamePhase($gameId);
                $this->set('result', "success");
            }
        }else{
            $this->set('result', "failure");
        }
    }
    
    public function saveNewAttack($gameId,$userId, $fromTileId, $toTileId, $numTroops) {
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $gameId])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        $this->loadModel('AttackActions');
        $newAttack = $this->AttackActions->newEntity();
        $newAttack->game_id = $gameId;
        $newAttack->game_user_id = $userId;
        $newAttack->turn_number = $gameInfo->last_completed_turn + 1;
        $newAttack->num_troops = $numTroops;
        $newAttack->attack_from = $fromTileId;
        $newAttack->attack_target = $toTileId;
        return $this->AttackActions->save($newAttack);
    }
    
    public function activePlayersCompletedPhase($gameId, $phase) {
        $this->loadModel('GamesUsers');
        $gameUsers = $this->GamesUsers->find()
                                    ->where(['game_id' => $gameId, 'is_bot' => 0])
                                    ->all()
                                    ->toArray();
        $activePlayersCompletedTurn = true;
        
        if($phase == 'deploy') {
            $this->loadModel('DeploymentActions');
            foreach($gameUsers as $gameUser){
                $actions = $this->DeploymentActions->find()
                                                ->where(['game_id' => $gameId, 'game_user_id' => $gameUser->user_id])
                                                ->all()->toArray();
                                                
                if($actions == NULL) {
                    $activePlayersCompletedTurn = false;
                    break;
                }
            }
        } else if($phase == 'move') {
            $this->loadModel('MoveActions');
            foreach($gameUsers as $gameUser){
                $actions = $this->MoveActions->find()
                                                ->where(['game_id' => $gameId, 'game_user_id' => $gameUser->user_id])
                                                ->all()->toArray();
                                                
                if($actions == NULL) {
                    $activePlayersCompletedTurn = false;
                    break;
                }
            }
        } else if($phase == 'attack') {
            $this->loadModel('AttackActions');
            foreach($gameUsers as $gameUser){
                $actions = $this->AttackActions->find()
                                                ->where(['game_id' => $gameId, 'game_user_id' => $gameUser->user_id])
                                                ->all()->toArray();
                                                
                if($actions == NULL) {
                    $activePlayersCompletedTurn = false;
                    break;
                }
            }
        }
        return $activePlayersCompletedTurn;
    }
    
    public function updateTerritoryAfterDeployment($gameId) {
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $gameId])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        $this->loadModel('DeploymentActions');
        $deployments = $this->DeploymentActions->find()
                                            ->where(['game_id' => $gameId, 'turn_number' => ($gameInfo->last_completed_turn + 1)])
                                            ->all()->toArray();
                                            
        foreach($deployments as $deployment) {
            $this->loadModel('Territories');
            $territories = $this->Territories->find()
                                            ->where(['game_id' => $gameId, 'tile_id' => $deployment->to_territory_id])
                                            ->all()->toArray();
            $territoryToUpdate = $this->Territories->get($territories[0]->id);
            $newTroopNum = $territoryToUpdate->num_troops + $deployment->num_troops;
            $territoryToUpdate->num_troops = $newTroopNum;
            $this->Territories->save($territoryToUpdate);
        }
    }
    
    public function updateTerritoryAfterMove($gameId) {
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $gameId])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        $this->loadModel('MoveActions');
        $moves = $this->MoveActions->find()
                                    ->where(['game_id' => $gameId, 'turn_number' => ($gameInfo->last_completed_turn + 1)])
                                    ->all()->toArray();
                                            
        foreach($moves as $move) {
            // If place holder move for players with only 1 territory and surrounded
            if ($move->from_territory_id == $move->to_territory_id){
                continue;
            }
            
            // Add troops to the destination territory
            $this->loadModel('Territories');
            $territories = $this->Territories->find()
                                            ->where(['game_id' => $gameId, 'tile_id' => $move->to_territory_id])
                                            ->all()->toArray();
            $territoryToUpdate = $this->Territories->get($territories[0]->id);
            $newTroopNum = $territoryToUpdate->num_troops + $move->num_troops;
            $territoryToUpdate->num_troops = $newTroopNum;
            $this->Territories->save($territoryToUpdate);
            
            // Remove troops from source territory
            $this->loadModel('Territories');
            $territories = $this->Territories->find()
                                            ->where(['game_id' => $gameId, 'tile_id' => $move->from_territory_id])
                                            ->all()->toArray();
            $territoryToUpdate = $this->Territories->get($territories[0]->id);
            $newTroopNum = $territoryToUpdate->num_troops - $move->num_troops;
            $territoryToUpdate->num_troops = $newTroopNum;
            $this->Territories->save($territoryToUpdate);
        }
    }
    
    public function updateTerritoryAfterAttack($gameId) {
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $gameId])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        $this->loadModel('AttackActions');
        $attacks = $this->AttackActions->find()
                                    ->where(['game_id' => $gameId, 'turn_number' => ($gameInfo->last_completed_turn + 1)])
                                    ->all()->toArray();
                          
        // Below was a first attempt at processing all moves fairly.
        // Kinda hard, especially thinking about what to do if territory 1
        // attacks territory 2, territory 2 attacks territory 3 and territory 3
        // attacks territory 1
        // $attackMap = array();
        // $attackToTerritory = array();
        // $attackMap['attackedTo'] = $attackToTerritory;
        // $attackFromTerritory = array();
        // $attackMap['attackedFrom'] = $attackToTerritory;
        
        // foreach($attacks as $attack) {
        //     array_push($attackMap['attackedTo'], $attack->attack_target);
        //     array_push($attackMap['attackedFrom'], $attack->attack_from);
        // }
        
        // foreach($attacks as $attack) {
        //     // If no one else is attacking the same target 
        //     if(!in_array($attack->attack_target,$attackMap['attackedTo'])) {
        //         // If no one is attacking the from target, process
        //         if(!in_array($attack->attack_from,$attackMap['attackedTo'])) {
        //             $this->performAttackBattle($attack);
        //         } else {
        //             //
        //         }
        //     }
        // }
        
        foreach($attacks as $attack) {
            $numAttackingTroops = $attack->num_troops;
            
            $this->loadModel('Territories');
            
            // Troops are removed from the attack_from territory.
            // troops either take over the target or die trying
            $territories = $this->Territories
                                ->find()
                                ->where(['game_id' => $gameId, 'tile_id' => $attack->attack_from])
                                ->all()->toArray();
            
            $territoryFrom = $this->Territories->get($territories[0]->id);
            
            // If user does not own territory anymore, continue on. They must have
            // lost a battle already for this territory
            if($attack->game_user_id != $territoryFrom->user_id) {
                continue;
            }
        
            // If the number of available troops in the territory is smaller than 
            // those used to attack, update. They must have lost troops defending
            // this territory already
            if($numAttackingTroops > ($territoryFrom->num_troops - 1)) {
                // Assume the player wants to use as many as they can
                $numAttackingTroops = $territoryToUpdate->num_troops - 1;
            }
        
            $newTroopNum = $territoryFrom->num_troops - $numAttackingTroops;
            $territoryFrom->num_troops = $newTroopNum;
            $this->Territories->save($territoryFrom);
            
            // Get the target territory
            $territories = $this->Territories
                                ->find()
                                ->where(['game_id' => $gameId, 'tile_id' => $attack->attack_target])
                                ->all()->toArray();
            $territoryAttacked = $territories[0];
            
            $result = $this->performAttackBattle($numAttackingTroops, $territoryAttacked->num_troops);
            $userId = 0;
            if($result > 0) {
                $userId = $attack->game_user_id;
                
                // Defender lost all troops
                $this->subtractGameUserNumTroops($gameId, $territoryAttacked->user_id, $territoryAttacked->num_troops);
                
                // Attacker lost some troops
                $this->subtractGameUserNumTroops($gameId, $attack->game_user_id, ($numAttackingTroops - $result));
            } else {
                $userId = $territoryAttacked->user_id;
                $result = abs($result);
                
                // Attacker lost all troops
                $this->subtractGameUserNumTroops($gameId, $attack->game_user_id, $numAttackingTroops);
                
                // Defender lost some troops
                $this->subtractGameUserNumTroops($gameId, $territoryAttacked->user_id, ($territoryAttacked->num_troops - $result));
            }
            
            $territoryToUpdate = $this->Territories->get($territoryAttacked->id);
            $territoryToUpdate->num_troops = $result;
            $territoryToUpdate->is_occupied = 1;
            $territoryToUpdate->user_id = $userId;
            $this->Territories->save($territoryToUpdate);
        }
        
    }
    
    // This function will perform the battle randomization between attacking
    // and defending troops. It will return the number of surviving troops
    // if the attack was successful. It will return a negative number if 
    // the attack failed with the absolute value of that number being the
    // number of troops remaining
    public function performAttackBattle($numAttackTroops, $numDefendTroops) {
        // If defending troops is 0, must be neutral territory
        if ($numDefendTroops == 0) {
            return $numAttackTroops;
        }
        
        $numAttackRemain = rand(0, $numAttackTroops);
        $numDefendRemain = rand(0, $numDefendTroops);
        
        if ($numAttackRemain > $numDefendRemain) {
            return $numAttackRemain;
        } else if ($numAttackRemain == 0 && $numDefendRemain == 0) {
            // All troops died but we dont want a draw
            // so randomly find the hidden last man standing
            if(rand(1,2) == 1) {
                return 1;
            } else {
                return -1;
            }
        } else {
            return 0 - $numDefendRemain;
        }
    }
    
    // This function will go through the Game Users that are bot accounts
    // and submit deployment actions
    public function processAiDeployment($gameId) {
        $this->loadModel('GamesUsers');
        $aiGameUsers = $this->GamesUsers->find()
                                    ->where(['game_id' => $gameId, 'is_bot' => 1])
                                    ->all()
                                    ->toArray();
        foreach($aiGameUsers as $aiUser){
            $this->loadModel('Territories');
            $territories = $this->Territories->find()
                                            ->where(['game_id' => $gameId, 'user_id' => $aiUser->user_id])
                                            ->order(['num_troops' => 'ASC'])
                                            ->all()->toArray();
            // Make sure user has territories. Otherwise they lost already
            if($territories != NULL){
                $territoryToDeploy = $territories[0];
                $numToDeploy = $this->getTroopsAvailableForUser($gameId, $aiUser->user_id);
                $this->saveNewDeploy($gameId,$aiUser->user_id,$territoryToDeploy->tile_id,$numToDeploy);
            }
        }
    }
    
    // This function will go through the Game Users that are bot accounts
    // and submit move actions
    public function processAiMove($gameId) {
        $this->loadModel('GamesUsers');
        $aiGameUsers = $this->GamesUsers->find()
                                    ->where(['game_id' => $gameId, 'is_bot' => 1])
                                    ->all()
                                    ->toArray();
        foreach($aiGameUsers as $aiUser){
            $this->loadModel('Territories');
            $territories = $this->Territories->find()
                                            ->where(['game_id' => $gameId, 'user_id' => $aiUser->user_id])
                                            ->order(['num_troops' => 'DESC'])
                                            ->all()->toArray();
            // Make sure user has territories. Otherwise they lost already
            if($territories != NULL){
                if(count($territories) == 1) {
                    // Bot only has 1 territory
                    $tId = $territories[0]->tile_id;
                    $this->saveNewMove($gameId,$aiUser->user_id, $tId, $tId, 0);
                } else {
                    $territoryFromId = $territories[0]->tile_id;
                    $territoryToId = $territories[1]->tile_id;
                    $numToMove= round($territories[0]->num_troops / 2);
                    $this->saveNewMove($gameId, $aiUser->user_id, $territoryFromId, $territoryToId, $numToMove);
                    
                }
            }
        }
    }
    
    // This function will go through the Game Users that are bot accounts
    // and submit attack actions
    public function processAiAttack($gameId) {
        debug('testing the ai attack');
        $this->loadModel('GamesUsers');
        $aiGameUsers = $this->GamesUsers->find()
                                    ->where(['game_id' => $gameId, 'is_bot' => 1])
                                    ->all()
                                    ->toArray();
        foreach($aiGameUsers as $aiUser){
            // Get owned territories ordered by number of troops
            $this->loadModel('Territories');
            $territories = $this->Territories->find()
                                            ->where(['game_id' => $gameId, 'user_id' => $aiUser->user_id])
                                            ->order(['num_troops' => 'DESC'])
                                            ->all()->toArray();
            if ($territories == NULL) {
                // Player is out
                continue;
            }
            // Go from most troops to least and see if there is an adjacent
            // tile that can be attacked. Try to take over empty ones before
            // Attacking owned territories
            foreach($territories as $territory) {
                $fromId = $territory->tile_id;
                $numTroops = round($territory->num_troops / 2);
                $neutralTerritory = $this->getNeutralAdjacentTerritory($gameId, $fromId);
                if ($neutralTerritory != NULL) {
                    $toId = $neutralTerritory->tile_id;
                    $this->saveNewAttack($gameId,$aiUser->user_id, $fromId, $toId, $numTroops);
                    break;
                }
                
                $territoryToAttack = $this->getAdjacentTerritoryToAttack($gameId, $aiUser->user_id, $fromId);
                if ($territoryToAttack != NULL) {
                    $toId = $territoryToAttack->tile_id;
                    $this->saveNewAttack($gameId,$aiUser->user_id, $fromId, $toId, $numTroops);
                    break;
                }
            }
            
            
        }
    }
    
    public function getNeutralAdjacentTerritory($gameId, $tileId) {
        $this->loadModel('Territories');
        
        $mapInfo = $this->getMapPoints();
        foreach($mapInfo['adjacentTerritories'][$tileId] as $tId){
            $territories = $this->Territories
                                ->find()
                                ->where(['game_id' => $gameId, 'is_occupied' => 0, 'tile_id' => $tId])
                                ->all()->toArray();
            if($territories != NULL) {
                return $territories[0];
            }
        }
        return NULL;
    }
    
    public function getAdjacentTerritoryToAttack($gameId, $userId, $tileId) {
        $this->loadModel('Territories');
        
        $mapInfo = $this->getMapPoints();
        foreach($mapInfo['adjacentTerritories'][$tileId] as $tId){
            $territories = $this->Territories
                                ->find()
                                ->where(['game_id' => $gameId, 'is_occupied' => 1, 'tile_id' => $tId])
                                ->all()->toArray();
            if($territories != NULL && $territories[0]->user_id != $userId) {
                return $territories[0];
            }
        }
        return NULL;
    }
    
    public function updateGamePhase($gameId){
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $gameId])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        echo $gameInfo;
        
        $currentPhase = $gameInfo->current_phase;
        
        echo $currentPhase;
        
        $gameToSave = $this->Games->get($gameInfo->id);
        
        if($currentPhase == 'deploy'){
            $gameToSave->current_phase = 'move';
        } else if ($currentPhase == 'move') {
            $gameToSave->current_phase = 'attack';
        } else if ($currentPhase == 'attack') {
            $this->updatePlayersAvailableTroops($gameId);
            $gameToSave->current_phase = 'deploy';
            $gameToSave->last_completed_turn = $gameToSave->last_completed_turn + 1;
        }
        $this->Games->save($gameToSave);
    }
    
    public function updatePlayersAvailableTroops($gameId) {
        $this->loadModel('GamesUsers');
        $gameUsers = $this->GamesUsers->find()
                                    ->where(['game_id' => $gameId])
                                    ->all()
                                    ->toArray();
                                    
        foreach($gameUsers as $user){
            $this->loadModel('Territories');
            $territories = $this->Territories
                                ->find()
                                ->where(['game_id' => $gameId, 'user_id' => $user->user_id])
                                ->all()->toArray();
            if($territories != NULL) {
                $numNewTroops = count($territories) * 2;
                $this->addGameUserNumTroops($gameId, $user->user_id, $numNewTroops);
            }                    
        }
    }
    
    public function addGameUserNumTroops($gameId, $userId, $numTroops) {
        if($userId == 1){
            return;
        }
        $this->loadModel('GamesUsers');
        $gameUsers = $this->GamesUsers->find()
                                    ->where(['game_id' => $gameId, 'user_id' => $userId])
                                    ->all()
                                    ->toArray();
        $userToUpdate = $this->GamesUsers->get($gameUsers[0]->id);
        $existingTroops = $userToUpdate->troops;
        $userToUpdate->troops = ($existingTroops + $numTroops);
        $this->GamesUsers->save($userToUpdate);
    }
    
    public function subtractGameUserNumTroops($gameId, $userId, $numTroops) {
        if($userId == 1){
            return;
        }
        $this->loadModel('GamesUsers');
        $gameUsers = $this->GamesUsers->find()
                                    ->where(['game_id' => $gameId, 'user_id' => $userId])
                                    ->all()
                                    ->toArray();
        $userToUpdate = $this->GamesUsers->get($gameUsers[0]->id);
        $existingTroops = $userToUpdate->troops;
        $userToUpdate->troops = ($existingTroops - $numTroops);
        $this->GamesUsers->save($userToUpdate);
    }
    
    //Ajax action
    //Return: all the data for a given map
    //
    //Currently holds all data for one map. Plan to move data to new location and
    //make it an option to have mulitple maps.
    public function getMap($game_id)
    {
        // Static ID for testing. we will pull this as a parameter
        //$game_id = 1;
        
        //For Ajax requests
        $this->viewBuilder()->layout('ajax');
        
        $this->loadModel('Territories');
        $territories = $this->Territories
                            ->find()
                            ->where(['game_id' => $game_id])
                            ->order(['tile_id' => 'ASC'])
                            ->all()->toArray();
        
        // Build array by tile ID type
        $territoryById = array();
        for ($i = 0; $i < 20; $i++) {
            $territoryById[$territories[$i]->tile_id] = $territories[$i];
        }
        
        $mapInfo = $this->getMapPoints();
        
        $this->loadModel('GamesUsers');
        $players = $this->GamesUsers
                        ->find()
                        ->where(['game_id' => $game_id])
                        ->all()->toArray();
        //user_id = 1 is our neutral territory account.
        $colors = array(
            1 => "Gray"
            );
        for($i = 0; $i < count($players); $i++){
            $colors[$players[$i]->user_id] = $players[$i]->color;
        }
        $game = array();
        $map = array();
         
        for($i = 0; $i < 20; $i++) {
            $userId = $territoryById[$i]->user_id;
            if($userId == NULL){
                $userId = 0;
            }
            $map[$i] = array(
                "points" => $mapInfo['points'][$i],
                "color" => $colors[$userId],
                "center" => $mapInfo['centers'][$i],
                "troops" => $territoryById[$i]->num_troops,
                "adjacentTerritories" => $mapInfo['adjacentTerritories'][$i],
                "owner" => $userId,
                "shape" => $mapInfo['shape'][$i]
                );
        }
        $game["map"] = $map;
        
        $this->loadModel('Games');
        $gamesInfo = $this->Games
                    ->find()
                    ->where(['id' => $game_id])
                    ->all()->toArray();
                    
        $gameInfo = $gamesInfo[0];
        
        $game["phase"] = $gameInfo->current_phase;
        $game["currentTurn"] = $gameInfo->last_completed_turn + 1;
        
        $currentUserId = $this->Auth->User('id');
        if($currentUserId == NULL) {
            $currentUserId = 2;
        }
        $troopsAvailable = $this->getTroopsAvailableForUser($game_id, $currentUserId);
        $game["troopsAvailable"] = $troopsAvailable;
        $game["userID"] = $currentUserId;
        $game["gameID"] = $game_id;
        
        //Set the map array to be available in the view with name of map
        $this->set('game', $game);
    }
    
    public function getTroopsAvailableForUser($gameId, $userId) {
        
        $this->loadModel('GamesUsers');
        $users = $this->GamesUsers->find()
                                ->where(['game_id' => $gameId, 'user_id' => $userId])
                                ->all()->toArray();
        $user = $users[0];
        
        $this->loadModel('Territories');
        $territories = $this->Territories
                            ->find()
                            ->where(['game_id' => $gameId, 'user_id' => $userId])
                            ->all()->toArray();
        $numTroopsAvailable = $user->troops;
        
        foreach($territories as $territory) {
            $numTroopsAvailable = $numTroopsAvailable - $territory->num_troops;
        }
        
        return $numTroopsAvailable;
    }
    
    // This function will build an array of map points. To begin we will have
    // one map but with potential to add more
    public function getMapPoints() {
        $mapPoints = array();
        $mapPointCenters = array();
        $adjacentTerritories = array();
        $shape = array();
        
        $mapPoints[0] = array( array('x' => 150, 'y' => 0), 
                        array( 'x' => 250, 'y' => 0),
                        array( 'x' => 300, 'y' => 116.666666667),
                        array( 'x' => 250, 'y' => 233.3333333334),
                        array( 'x' => 150, 'y' => 233.3333333334),
                        array( 'x'=> 100, 'y' => 116.666667));
        $mapPointCenters[0] = array( 'x' => 200, 'y' => 116.666667);
        $adjacentTerritories[0] = array(1, 3, 4);
        $shape[0] = "Hexagon";
        
        $mapPoints[1] = array( array('x' => 250, 'y' => 0), 
                        array( 'x' => 450, 'y' => 0),
                        array( 'x' => 400, 'y' => 116.666666667),
                        array( 'x' => 300, 'y' => 116.666666667));
        $mapPointCenters[1] = array( 'x' => 350, 'y' => 58.33334);
        $adjacentTerritories[1] = array(0, 2, 4);
        $shape[1] = "Trapezoid";
        
        $mapPoints[2] = array( array('x' => 450, 'y' => 0), 
                        array( 'x' => 550, 'y' => 0),
                        array( 'x' => 600, 'y' => 116.666666667),
                        array( 'x' => 550, 'y' => 233.3333333334),
                        array( 'x' => 450, 'y' => 233.3333333334),
                        array( 'x'=> 400, 'y' => 116.666667));
        $mapPointCenters[2] = array( 'x' => 500, 'y' => 116.6666667);
        $adjacentTerritories[2] = array(1, 4, 5);
        $shape[2] = "Hexagon";
        
        $mapPoints[3] = array( array('x' => 100, 'y' => 116.666667), 
                        array( 'x' => 50, 'y' => 233.33334),
                        array( 'x' => 150, 'y' => 233.333334));
        $mapPointCenters[3] = array( 'x' => 100, 'y' => 194);
        $adjacentTerritories[3] = array(0, 6);
        $shape[3] = "Triangle";
        
        $mapPoints[4] = array( array('x' => 300, 'y' => 116.66667), 
                        array( 'x' => 400, 'y' => 116.666667),
                        array( 'x' => 450, 'y' => 233.3333334),
                        array( 'x'=> 250, 'y' => 233.33334));
        $mapPointCenters[4] = array( 'x' => 350, 'y' => 175);
        $adjacentTerritories[4] = array(0, 1, 2, 8);
        $shape[4] = "Trapezoid";
        
        $mapPoints[5] = array( array('x' => 600, 'y' => 116.666667), 
                        array( 'x' => 550, 'y' => 233.33334),
                        array( 'x' => 650, 'y' => 233.333334));
        $mapPointCenters[5] = array( 'x' => 600, 'y' => 194);
        $adjacentTerritories[5] = array(2, 13);
        $shape[5] = "Triangle";
        
        $mapPoints[6] = array( array('x' => 50, 'y' => 233.333334), 
                        array( 'x' => 150, 'y' => 233.333334),
                        array( 'x' => 200, 'y' => 350),
                        array( 'x' => 150, 'y' => 466.6666667),
                        array( 'x' => 50, 'y' => 466.6666667),
                        array( 'x'=> 0, 'y' => 350));
        $mapPointCenters[6] = array( 'x' => 100, 'y' => 350);
        $adjacentTerritories[6] = array(3, 7, 11, 14);
        $shape[6] = "Hexagon";
        
        $mapPoints[7] = array( array('x' => 150, 'y' => 233.333334), 
                        array( 'x' => 250, 'y' => 233.33334),
                        array( 'x' => 300, 'y' => 350),
                        array( 'x'=> 200, 'y' => 350));
        $mapPointCenters[7] =array( 'x' => 225, 'y' => 291.666667);
        $adjacentTerritories[7] = array(0, 6, 8, 11);
        $shape[7] = "Rhombus";
        
        $mapPoints[8] = array( array('x' => 250, 'y' => 233.333334), 
                        array( 'x' => 450, 'y' => 233.33334),
                        array( 'x' => 400, 'y' => 350),
                        array( 'x'=> 300, 'y' => 350));
        $mapPointCenters[8] = array( 'x' => 350, 'y' => 291.666667);
        $adjacentTerritories[8] = array(4, 7, 9, 10);
        $shape[8] = "Trapezoid";
        
        $mapPoints[9] = array( array('x' => 450, 'y' => 233.333334), 
                        array( 'x' => 550, 'y' => 233.3333334),
                        array( 'x' => 500, 'y' => 350),
                        array( 'x'=> 400, 'y' => 350));
        $mapPointCenters[9] = array( 'x' => 475, 'y' => 291.666667);
        $adjacentTerritories[9] = array(2, 8, 12, 13);
        $shape[9] = "Rhombus";
        
        $mapPoints[10] = array( array('x' => 300, 'y' => 350), 
                        array( 'x' => 400, 'y' => 350),
                        array( 'x' => 450, 'y' => 466.666667),
                        array( 'x'=> 250, 'y' => 466.66667));
        $mapPointCenters[10] = array( 'x' => 350, 'y' => 408.333334);
        $adjacentTerritories[10] = array(8, 11, 12, 17);
        $shape[10] = "Trapezoid";
        
        $mapPoints[11] = array( array('x' => 200, 'y' => 350), 
                        array( 'x' => 300, 'y' => 350),
                        array( 'x' => 250, 'y' => 466.666667),
                        array( 'x'=> 150, 'y' => 466.66667));
        $mapPointCenters[11] = array( 'x' => 225, 'y' => 408.33334);
        $adjacentTerritories[11] = array(6, 7, 10, 16);
        $shape[11] = "Rhombus";
        
        $mapPoints[12] = array( array('x' => 400, 'y' => 350), 
                        array( 'x' => 500, 'y' => 350),
                        array( 'x' => 550, 'y' => 466.66667),
                        array( 'x'=> 450, 'y' => 466.66667));
        $mapPointCenters[12] = array( 'x' => 475, 'y' => 408.33334);
        $adjacentTerritories[12] = array(9, 10, 13, 19);
        $shape[12] = "Rhombus";
        
        $mapPoints[13] = array( array('x' => 550, 'y' => 233.333334), 
                        array( 'x' => 650, 'y' => 233.333334),
                        array( 'x' => 700, 'y' => 350),
                        array( 'x' => 650, 'y' => 466.6666667),
                        array( 'x' => 550, 'y' => 466.6666667),
                        array( 'x'=> 500, 'y' => 350));
        $mapPointCenters[13] = array( 'x' => 600, 'y' => 350);
        $adjacentTerritories[13] = array(5, 9, 12, 15);
        $shape[13] = "Hexagon";
        
        $mapPoints[14] = array( array('x' => 150, 'y' => 466.6666667),
                      array('x' => 50, 'y' =>  466.6666667),
                      array('x' => 100, 'y' => 583.333334));
        $mapPointCenters[14] = array('x' => 100, 'y' => 505.5555556);
        $adjacentTerritories[14] = array(6, 16);
        $shape[14] = "Triangle";
        
        $mapPoints[15] = array( array('x' => 550, 'y' => 466.6666667),
                       array('x' => 650, 'y' => 466.6666667),
                       array('x' => 600, 'y' => 583.333334));
        $mapPointCenters[15] = array('x' => 600, 'y' => 505.5555556);
        $adjacentTerritories[15] = array(13, 19);
        $shape[15] = "Triangle";
        
        $mapPoints[16] = array(array('x' => 150, 'y' => 466.6666667),
                        array('x' => 250, 'y' => 466.6666667),
                        array('x' => 300, 'y' => 583.3333334),
                        array('x' => 250, 'y' => 700),
                        array('x' => 150, 'y' => 700),
                        array('x' => 100, 'y' => 583.3333334));
        $mapPointCenters[16] = array('x' => 200, 'y' => 583.3333334);
        $adjacentTerritories[16] = array(11, 14, 17, 18);
        $shape[16] = "Hexagon";
        
        $mapPoints[17] = array( array('x' => 250, 'y' => 466.6666667),
                          array('x' => 450, 'y' => 466.6666667),
                          array('x' => 400, 'y' => 583.3333334),
                          array('x' => 300, 'y' => 583.3333334));
        $mapPointCenters[17] = array('x' => 350, 'y' => 525);
        $adjacentTerritories[17] = array(10, 16, 18, 19);
        $shape[17] = "Trapezoid";
        
        $mapPoints[18] = array( array('x' => 300, 'y' => 583.3333334),
                        array('x' => 400, 'y' => 583.3333334),
                        array('x' => 450, 'y' => 700),
                        array('x' => 250, 'y' => 700));
        $mapPointCenters[18] = array('x' => 350, 'y' => 641.6666667);
        $adjacentTerritories[18] = array(16, 17, 19);
        $shape[18] = "Trapezoid";
        
        $mapPoints[19] = array(array('x' => 450, 'y' => 466.6666667),
                        array('x' => 550, 'y' => 466.6666667),
                        array('x' => 600, 'y' => 583.3333334),
                        array('x' => 550, 'y' => 700),
                        array('x' => 450, 'y' => 700),
                        array('x' => 400, 'y' => 583.3333334));
        $mapPointCenters[19] = array('x' => 500, 'y' => 583.3333334);
        $adjacentTerritories[19] = array(12, 15, 17, 18);
        $shape[19] = "Hexagon";
        
        $mapInfo = array();
        $mapInfo['points'] = $mapPoints;
        $mapInfo['centers'] = $mapPointCenters;
        $mapInfo['adjacentTerritories'] = $adjacentTerritories;
        $mapInfo['shape'] = $shape;
        
        return $mapInfo;
    }
    
    
    //List games that have not filled up with users yet that are open to join
    public function calculate()
    {
        //For Ajax requests
        $this->viewBuilder()->layout('ajax');
        
        //Set up connections to all requred tables
        $Games = TableRegistry::get('Games');
        $Territories = TableRegistry::get('Territories');
        $GamesUsers = TableRegistry::get('GamesUsers');
        $Users = TableRegistry::get('Users');
        
        
        //Find list of games that have not started but are part the set start time
        $gameResults = $Games->find()->
            where(["start_time <" => time() * 1000])->
            where(["started = 0" ]);
            
        
        $players = array();
        
        //Iterate through each game that needs to start
        foreach ($gameResults as $game) {
            
            echo "Game Found";
            
            //Uncomment below to see list of unstarted games that will be started
            //echo $game;
            
            //Array that will hold list of players for game
            $players = array();
            
            //Gets a list of users that are going to play current game
            $gameUsers = $GamesUsers->find()->
                where(["game_id =" => $game->id]);
            
            //Iterate through players and push to players array
            foreach ($gameUsers as $gameUser) {
                
                
                
                array_push($players, $gameUser->user_id);
                
            }
            
            
            echo " with " . count($players) . " players";
            
            //Hexagon locations for game start- 0, 2, 6, 13, 16, 19
            
            //Create 20 territores for our game
            for ($x = 0; $x < 20; $x++) {
                $newTerritory = $Territories->newEntity();
                
                $newTerritory->game_id = $game->id;
                $newTerritory->turn_id = 1;
                $newTerritory->tile_id = $x;
                
                if($x == 0 && count($players) > 0) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[0];
                    $newTerritory->num_troops = 20;
                } elseif ($x == 2 && count($players) > 1) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[1];
                    $newTerritory->num_troops = 20;
                } elseif ($x == 6 && count($players) > 2) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[2];
                    $newTerritory->num_troops = 20;
                } elseif ($x == 13 && count($players) > 3) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[3];
                    $newTerritory->num_troops = 20;
                } elseif ($x == 16 && count($players) > 4) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[4];
                    $newTerritory->num_troops = 20;
                } elseif ($x == 19 && count($players) > 5) {
                    $newTerritory->is_occupied = 1;
                    $newTerritory->user_id = $players[5];
                    $newTerritory->num_troops = 20;
                } else {
                    $newTerritory->is_occupied = 0;
                    $newTerritory->user_id = 1;
                    $newTerritory->num_troops = 0;
                }
                
                echo ". Creating territory";  
                if(!$Territories->save($newTerritory)) {
                    //echo ".  Territory Created";
                    debug($this->validationErrors); die();
                }
                
               // $newTerritory->is_occupied =
                //$newTerritory->user_id =
                //$newTerritory->num_troops =
            }
            
            //Mark game as started
            $game->started = 1;
            
            //Save game state
            $Games->save($game);
            
        }
    }
}
?>