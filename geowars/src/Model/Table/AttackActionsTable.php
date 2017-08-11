<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AttackActions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Games
 *
 * @method \App\Model\Entity\AttackAction get($primaryKey, $options = [])
 * @method \App\Model\Entity\AttackAction newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AttackAction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AttackAction|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AttackAction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AttackAction[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AttackAction findOrCreate($search, callable $callback = null, $options = [])
 */
class AttackActionsTable extends Table
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

        $this->table('attack_actions');
        $this->displayField('turn_id');
        $this->primaryKey(['attack_target', 'attack_from']);

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
            ->integer('attack_target')
            ->allowEmpty('attack_target', 'create');

        $validator
            ->integer('attack_from')
            ->allowEmpty('attack_from', 'create');

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
