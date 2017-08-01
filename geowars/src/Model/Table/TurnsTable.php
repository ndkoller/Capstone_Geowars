<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Turns Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Games
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $AttackActions
 * @property \Cake\ORM\Association\HasMany $DeploymentActions
 * @property \Cake\ORM\Association\HasMany $Territories
 *
 * @method \App\Model\Entity\Turn get($primaryKey, $options = [])
 * @method \App\Model\Entity\Turn newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Turn[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Turn|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Turn patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Turn[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Turn findOrCreate($search, callable $callback = null, $options = [])
 */
class TurnsTable extends Table
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

        $this->table('turns');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AttackActions', [
            'foreignKey' => 'turn_id'
        ]);
        $this->hasMany('DeploymentActions', [
            'foreignKey' => 'turn_id'
        ]); 
        $this->hasMany('Territories', [
            'foreignKey' => 'turn_id'
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
