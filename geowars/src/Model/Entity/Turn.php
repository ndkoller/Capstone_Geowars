<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Turn Entity
 *
 * @property int $id
 * @property int $game_id
 * @property int $user_id
 *
 * @property \App\Model\Entity\Game $game
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\AttackAction[] $attack_actions
 * @property \App\Model\Entity\DeploymentAction[] $deployment_actions
 * @property \App\Model\Entity\Territory[] $territories
 */
class Turn extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
