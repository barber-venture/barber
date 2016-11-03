<?php
namespace App\Model\Table;

use App\Model\Entity\UserTag;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserTags Model
 *
 * @property \Cake\ORM\Association\HasMany $UserTagAssociations
 */
class UserTagsTable extends Table
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

        $this->table('user_tags');
        $this->displayField('name');
        $this->primaryKey('id');

        //$this->hasMany('UserTagAssociations', [
        //    'foreignKey' => 'user_tag_id'
        //]);
        
        //$this->belongsToMany('UserTagAssociations', [
        //    'targetForeignKey' => 'user_id',
        //    'foreignKey' => 'user_tag_id',
        //    'joinTable' => 'user_tag_associations',
        //]);
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->boolean('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        return $validator;
    }

    public function get_list($fields = array()){
		$data = $this->find('list',array(
				'conditions'=>array(
					'status'=>1,
				),
				'fields'=>$fields,
				'order'=>'name'
			));
		return $data;
	}
}
