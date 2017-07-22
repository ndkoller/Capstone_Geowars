<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Territory Entity
 *
 * @property int $id
 * @property int $game_id
 * @property int $turn_id
 * @property bool $is_occupied
 * @property int $user_id
 * @property int $num_troops
 *
 * @property \App\Model\Entity\Game $game
 * @property \App\Model\Entity\Turn $turn
 * @property \App\Model\Entity\User $user
 */
class Territory extends Entity
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
