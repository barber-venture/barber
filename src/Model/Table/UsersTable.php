<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Roles
 * @property \Cake\ORM\Association\HasMany $AlbumImages
 * @property \Cake\ORM\Association\HasMany $Albums
 * @property \Cake\ORM\Association\HasMany $DislikeUsers
 * @property \Cake\ORM\Association\HasMany $Favorites
 * @property \Cake\ORM\Association\HasMany $SiteLogs
 * @property \Cake\ORM\Association\HasMany $UserDetails
 * @property \Cake\ORM\Association\HasMany $UserPlanAssociations
 * @property \Cake\ORM\Association\HasMany $UserSocialLink
 * @property \Cake\ORM\Association\HasMany $UserTagAssociations
 * @property \Cake\ORM\Association\HasMany $Visiters
 */
class UsersTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('users');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);
        $this->hasOne('UserDetails', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('AlbumImages', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Albums', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('LikeDislikes', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Favorites', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('SiteLogs', [
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('UserPlanAssociations', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserSocialLink', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('UserTagAssociations', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Visiters', [
            'foreignKey' => 'user_id'
        ]);
        
        $this->belongsToMany('UserTags', [
            'targetForeignKey' => 'user_tag_id',
            'foreignKey' => 'user_id',
            'joinTable' => 'user_tag_associations',
        ]);
        
    }
    

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->allowEmpty('user_code');

        $validator
                ->requirePresence('name', 'create')
                ->notEmpty('name');

        $validator
                ->email('email')
                ->requirePresence('email', false)
                ->notEmpty('email');

        //$validator
                //->requirePresence('password', 'create');
                //->notEmpty('password');

        //$validator
        //        ->requirePresence('activation_key', 'create')
        //        ->notEmpty('activation_key');

        $validator
                ->boolean('status')
                ->requirePresence('status', 'create')
                ->notEmpty('status');

        //$validator
        //        ->boolean('is_verify')
        //        ->requirePresence('is_verify', 'create')
        //        ->notEmpty('is_verify');

        $validator
                ->requirePresence('user_type', false)
                ->notEmpty('user_type');

        //$validator
        //        ->date('date_password')
        //        ->requirePresence('date_password', 'create')
        //        ->notEmpty('date_password');

        //$validator
        //        ->boolean('is_online')
        //        ->requirePresence('is_online', 'create')
        //        ->notEmpty('is_online');

        //$validator
                //->boolean('is_discovery')
                //->requirePresence('is_discovery', false)
                //->notEmpty('is_discovery');

        //$validator
        //        ->integer('change_password_count')
        //        ->requirePresence('change_password_count', 'create')
        //        ->notEmpty('change_password_count');

        //$validator
        //        ->boolean('deleted')
        //        ->requirePresence('deleted', 'create')
        //        ->notEmpty('deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
        return $rules;
    }
    
    public function get_list($fields = array()){
		$data = $this->find('list',array(
                'conditions' => ['deleted' => 0, 'role_id >' => 1],
				'fields'=>$fields,                
				'order'=>'name'
			));
		return $data;
	}
    
    public function setPassword($value, $password)
    {
        if (strlen($value) > 0) {
          return (new DefaultPasswordHasher)->check($value, $password);
        }
    }

}
