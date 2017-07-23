<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GamesUsers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Games
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\GamesUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\GamesUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GamesUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GamesUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GamesUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GamesUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GamesUser findOrCreate($search, callable $callback = null, $options = [])
 */
class GamesUsersTable extends Table
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

        $this->table('games_users');
        $this->displayField('game_id');
        $this->primaryKey(['game_id', 'user_id']);

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->boolean('is_bot')
            ->requirePresence('is_bot', 'create')
            ->notEmpty('is_bot');

        $validator
            ->integer('coins')
            ->requirePresence('coins', 'create')
            ->notEmpty('coins');

        $validator
            ->integer('troops')
            ->requirePresence('troops', 'create')
            ->notEmpty('troops');

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
        $rules->add($rules->existsIn(['game_id'], 'Games'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
