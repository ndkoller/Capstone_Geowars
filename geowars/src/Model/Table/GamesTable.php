<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Games Model
 *
 * @property \Cake\ORM\Association\HasMany $AttackActions
 * @property \Cake\ORM\Association\HasMany $DeploymentActions
 * @property \Cake\ORM\Association\HasMany $MoveActions
 * @property \Cake\ORM\Association\HasMany $Territories
 * @property \Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \App\Model\Entity\Game get($primaryKey, $options = [])
 * @method \App\Model\Entity\Game newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Game[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Game|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Game patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Game[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Game findOrCreate($search, callable $callback = null, $options = [])
 */
class GamesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('games');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('AttackActions', [
            'foreignKey' => 'game_id'
        ]);
        $this->hasMany('DeploymentActions', [
            'foreignKey' => 'game_id'
        ]);
        $this->hasMany('MoveActions', [
            'foreignKey' => 'game_id'
        ]);
        $this->hasMany('Territories', [
            'foreignKey' => 'game_id'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'game_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'games_users'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('started')
            ->requirePresence('started', 'create')
            ->notEmpty('started');

        $validator
            ->boolean('completed')
            ->requirePresence('completed', 'create')
            ->notEmpty('completed');

        $validator
            ->requirePresence('current_phase', 'create')
            ->notEmpty('current_phase');

        $validator
            ->requirePresence('turn_end_time', 'create')
            ->notEmpty('turn_end_time');

        $validator
            ->integer('map')
            ->allowEmpty('map');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

        $validator
            ->integer('phase_one_duration')
            ->requirePresence('phase_one_duration', 'create')
            ->notEmpty('phase_one_duration');

        $validator
            ->integer('phase_two_duration')
            ->requirePresence('phase_two_duration', 'create')
            ->notEmpty('phase_two_duration');

        $validator
            ->allowEmpty('start_time');

        $validator
            ->integer('min_users')
            ->allowEmpty('min_users');

        $validator
            ->integer('max_users')
            ->allowEmpty('max_users');

        $validator
            ->integer('join_opt')
            ->allowEmpty('join_opt');

        $validator
            ->integer('atStart_opt')
            ->allowEmpty('atStart_opt');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        

        return $rules;
    }
}
