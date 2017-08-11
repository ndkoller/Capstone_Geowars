<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GamesUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GamesUsersTable Test Case
 */
class GamesUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GamesUsersTable
     */
    public $GamesUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.games_users',
        'app.games',
        'app.last_completed_turns',
        'app.attack_actions',
        'app.game_users',
        'app.deployment_actions',
        'app.to_territories',
        'app.move_actions',
        'app.from_territories',
        'app.territories',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('GamesUsers') ? [] : ['className' => 'App\Model\Table\GamesUsersTable'];
        $this->GamesUsers = TableRegistry::get('GamesUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GamesUsers);

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
