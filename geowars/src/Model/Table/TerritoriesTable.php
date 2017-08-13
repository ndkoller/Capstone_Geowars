<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Territories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Games
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Territory get($primaryKey, $options = [])
 * @method \App\Model\Entity\Territory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Territory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Territory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Territory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Territory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Territory findOrCreate($search, callable $callback = null, $options = [])
 */
class TerritoriesTable extends Table
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

        $this->table('territories');
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
            ->boolean('is_occupied')
            ->requirePresence('is_occupied', 'create')
            ->notEmpty('is_occupied');

        $validator
            ->integer('num_troops')
            ->requirePresence('num_troops', 'create')
            ->notEmpty('num_troops');
        
        $validator
            ->integer('tile_id')
            ->requirePresence('tile_id', 'create')
            ->notEmpty('tile_id');
        
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
