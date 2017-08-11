<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MoveActionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MoveActionsTable Test Case
 */
class MoveActionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MoveActionsTable
     */
    public $MoveActions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.move_actions',
        'app.games',
        'app.last_completed_turns',
        'app.attack_actions',
        'app.deployment_actions',
        'app.territories',
        'app.users',
        'app.games_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MoveActions') ? [] : ['className' => 'App\Model\Table\MoveActionsTable'];
        $this->MoveActions = TableRegistry::get('MoveActions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MoveActions);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
