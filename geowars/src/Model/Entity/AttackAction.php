<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AttackAction Entity
 *
 * @property int $turn_id
 * @property int $attack_target
 * @property int $attack_from
 * @property int $num_troops
 *
 * @property \App\Model\Entity\Turn $turn
 */
class AttackAction extends Entity
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
        'turn_id' => false,
        'attack_target' => false,
        'attack_from' => false
    ];
}
