<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Game Entity
 *
 * @property int $id
 * @property bool $completed
 * @property int $last_completed_turn_id
 * @property int $turn_end_time
 * @property int $map
 *
 * @property \App\Model\Entity\LastCompletedTurn $last_completed_turn
 * @property \App\Model\Entity\Territory[] $territories
 * @property \App\Model\Entity\Turn[] $turns
 * @property \App\Model\Entity\User[] $users
 */
class Game extends Entity
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
