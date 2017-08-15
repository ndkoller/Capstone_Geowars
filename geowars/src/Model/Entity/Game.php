<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Game Entity
 *
 * @property int $id
 * @property int $started
 * @property bool $completed
 * @property int $last_completed_turn
 * @property string $current_phase
 * @property int $bot_hard_mode
 * @property int $turn_end_time
 * @property int $map
 * @property int $created_by
 * @property int $phase_one_duration
 * @property int $phase_two_duration
 * @property int $start_time
 * @property int $min_users
 * @property int $max_users
 * @property int $join_opt
 * @property int $atStart_opt
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
