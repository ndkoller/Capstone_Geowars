<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TerritoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TerritoriesTable Test Case
 */
class TerritoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TerritoriesTable
     */
    public $Territories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.territories',
        'app.games',
        'app.last_completed_turns',
        'app.turns',
        'app.users',
        'app.attack_actions',
        'app.deployment_actions',
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
        $config = TableRegistry::exists('Territories') ? [] : ['className' => 'App\Model\Table\TerritoriesTable'];
        $this->Territories = TableRegistry::get('Territories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Territories);

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
