<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GamesUser Entity
 *
 * @property int $id
 * @property int $game_id
 * @property int $user_id
 * @property bool $is_bot
 * @property int $troops
 * @property string $color
 *
 * @property \App\Model\Entity\Game $game
 * @property \App\Model\Entity\User $user
 */
class GamesUser extends Entity
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
        'user_id' => false
    ];
}
