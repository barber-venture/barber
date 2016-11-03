<?php
namespace App\Model\Table;

use App\Model\Entity\UserDetail;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserDetails Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Countries
 * @property \Cake\ORM\Association\BelongsTo $Cities
 * @property \Cake\ORM\Association\BelongsTo $States
 */
class UserDetailsTable extends Table
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

        $this->table('user_details');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Countries', [
            'foreignKey' => false,            
            'conditions' => ['Countries.country_id = UserDetails.country_id']
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('States', [
            'foreignKey' => 'state_id',
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

        //$validator
        //    ->requirePresence('address1', 'create')
        //    ->notEmpty('address1');
        //
        //$validator
        //    ->requirePresence('address2', false)
        //    ->notEmpty('address2');

        $validator
            ->requirePresence('nike_name', false)
            ->notEmpty('nike_name');

        $validator
            //->date('dob')
            ->requirePresence('dob', false)
            ->notEmpty('dob');

        $validator
            //->boolean('gender')
            ->requirePresence('gender', false)
            ->notEmpty('gender');

        $validator
            ->requirePresence('phone', false)
            ->notEmpty('phone');

        $validator
            ->requirePresence('mobile', 'create')
            ->notEmpty('mobile');

        $validator
            ->requirePresence('profile_image', false)
            ->notEmpty('profile_image');

        //$validator
        //    ->requirePresence('benar_image', 'create')
        //    ->notEmpty('benar_image');

        //$validator
        //    ->requirePresence('about_me', false)
        //    ->notEmpty('about_me');
        //    
        //$validator
        //    ->requirePresence('country_id', false)
        //    ->notEmpty('country_id');
        //    
        //$validator
        //    ->requirePresence('state_id', false)
        //    ->notEmpty('state_id');
        //    
        //$validator
        //    ->requirePresence('city_id', false)
        //    ->notEmpty('city_id');
            
        $validator
            ->requirePresence('address', false)
            ->notEmpty('address');
            
        $validator
            ->requirePresence('lat', false)
            ->notEmpty('lat');
        
        $validator
            ->requirePresence('lng', false)
            ->notEmpty('lng');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        //$rules->add($rules->existsIn(['country_id'], 'Countries'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));
        $rules->add($rules->existsIn(['state_id'], 'States'));
        return $rules;
    }
}
