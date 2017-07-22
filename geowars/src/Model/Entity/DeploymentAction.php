<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DeploymentAction Entity
 *
 * @property int $turn_id
 * @property int $coins_added
 * @property int $territory_id_attached
 * @property int $num_troops
 *
 * @property \App\Model\Entity\Turn $turn
 */
class DeploymentAction extends Entity
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
        'territory_id_attached' => false
    ];
}
