<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MoveActions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Games
 * @property \Cake\ORM\Association\BelongsTo $GameUsers
 * @property \Cake\ORM\Association\BelongsTo $FromTerritories
 * @property \Cake\ORM\Association\BelongsTo $ToTerritories
 *
 * @method \App\Model\Entity\MoveAction get($primaryKey, $options = [])
 * @method \App\Model\Entity\MoveAction newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MoveAction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MoveAction|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MoveAction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MoveAction[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MoveAction findOrCreate($search, callable $callback = null, $options = [])
 */
class MoveActionsTable extends Table
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

        $this->table('move_actions');
        $this->displayField('game_id');
        $this->primaryKey(['game_id', 'game_user_id', 'turn_number']);

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id',
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
            ->integer('turn_number')
            ->allowEmpty('turn_number', 'create');

        $validator
            ->integer('num_troops')
            ->requirePresence('num_troops', 'create')
            ->notEmpty('num_troops');

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

        return $rules;
    }
}
