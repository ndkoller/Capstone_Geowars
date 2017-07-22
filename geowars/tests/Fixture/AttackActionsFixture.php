<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AttackActionsFixture
 *
 */
class AttackActionsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'turn_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'attack_target' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'attack_from' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'num_troops' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'attack_target' => ['type' => 'index', 'columns' => ['attack_target'], 'length' => []],
            'attack_from' => ['type' => 'index', 'columns' => ['attack_from'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['turn_id', 'attack_target', 'attack_from'], 'length' => []],
            'attack_actions_ibfk_1' => ['type' => 'foreign', 'columns' => ['turn_id'], 'references' => ['turns', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'attack_actions_ibfk_2' => ['type' => 'foreign', 'columns' => ['attack_target'], 'references' => ['territories', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'attack_actions_ibfk_3' => ['type' => 'foreign', 'columns' => ['attack_from'], 'references' => ['territories', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'turn_id' => 1,
            'attack_target' => 1,
            'attack_from' => 1,
            'num_troops' => 1
        ],
    ];
}
