<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DeploymentActionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DeploymentActionsTable Test Case
 */
class DeploymentActionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DeploymentActionsTable
     */
    public $DeploymentActions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.deployment_actions',
        'app.turns',
        'app.games',
        'app.last_completed_turns',
        'app.territories',
        'app.users',
        'app.games_users',
        'app.attack_actions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DeploymentActions') ? [] : ['className' => 'App\Model\Table\DeploymentActionsTable'];
        $this->DeploymentActions = TableRegistry::get('DeploymentActions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DeploymentActions);

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
