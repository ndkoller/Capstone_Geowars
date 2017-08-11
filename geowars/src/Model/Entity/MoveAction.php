<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MoveAction Entity
 *
 * @property int $game_id
 * @property int $game_user_id
 * @property int $turn_number
 * @property int $from_territory_id
 * @property int $to_territory_id
 * @property int $num_troops
 *
 * @property \App\Model\Entity\Game $game
 */
class MoveAction extends Entity
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
        'game_id' => false,
        'game_user_id' => false,
        'turn_number' => false
    ];
}
