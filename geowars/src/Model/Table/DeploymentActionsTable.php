<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DeploymentActions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $DeploymentActions
 * @property \Cake\ORM\Association\BelongsTo $Games
 * @property \Cake\ORM\Association\BelongsTo $ToTerritories
 *
 * @method \App\Model\Entity\DeploymentAction get($primaryKey, $options = [])
 * @method \App\Model\Entity\DeploymentAction newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DeploymentAction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DeploymentAction|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DeploymentAction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DeploymentAction[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DeploymentAction findOrCreate($search, callable $callback = null, $options = [])
 */
class DeploymentActionsTable extends Table
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

        $this->table('deployment_actions');
        $this->displayField('deployment_action_id');
        $this->primaryKey('deployment_action_id');

        $this->belongsTo('DeploymentActions', [
            'foreignKey' => 'deployment_action_id',
            'joinType' => 'INNER'
        ]);
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
            ->requirePresence('turn_number', 'create')
            ->notEmpty('turn_number');

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
        $rules->add($rules->existsIn(['deployment_action_id'], 'DeploymentActions'));
        $rules->add($rules->existsIn(['game_id'], 'Games'));

        return $rules;
    }
}
