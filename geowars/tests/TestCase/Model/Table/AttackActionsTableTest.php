<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AttackActionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AttackActionsTable Test Case
 */
class AttackActionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AttackActionsTable
     */
    public $AttackActions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.attack_actions',
        'app.turns',
        'app.games',
        'app.last_completed_turns',
        'app.territories',
        'app.users',
        'app.games_users',
        'app.deployment_actions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AttackActions') ? [] : ['className' => 'App\Model\Table\AttackActionsTable'];
        $this->AttackActions = TableRegistry::get('AttackActions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AttackActions);

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
