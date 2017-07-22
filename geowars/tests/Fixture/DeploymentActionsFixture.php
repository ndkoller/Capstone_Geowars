<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DeploymentActionsFixture
 *
 */
class DeploymentActionsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'turn_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'coins_added' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'territory_id_attached' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'num_troops' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'territory_id_attached' => ['type' => 'index', 'columns' => ['territory_id_attached'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['turn_id', 'territory_id_attached'], 'length' => []],
            'deployment_actions_ibfk_1' => ['type' => 'foreign', 'columns' => ['turn_id'], 'references' => ['turns', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'deployment_actions_ibfk_2' => ['type' => 'foreign', 'columns' => ['territory_id_attached'], 'references' => ['territories', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'coins_added' => 1,
            'territory_id_attached' => 1,
            'num_troops' => 1
        ],
    ];
}
