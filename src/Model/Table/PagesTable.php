<?php
namespace App\Model\Table;

use App\Model\Entity\Page;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Inflector;

/**
 * Pages Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentPages
 * @property \Cake\ORM\Association\HasMany $ChildPages
 */
class PagesTable extends Table
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

        $this->table('pages');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ParentPages', [
            'className' => 'Pages',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildPages', [
            'className' => 'Pages',
            'foreignKey' => 'parent_id'
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
            ->requirePresence('page_key', 'create')
            ->notEmpty('page_key');

        $validator
            ->requirePresence('page_headline', 'create')
            ->notEmpty('page_headline');

        $validator
            ->requirePresence('page_title', 'create')
            ->notEmpty('page_title');

        $validator
            ->requirePresence('content', 'create')
            ->notEmpty('content');

        $validator
            ->requirePresence('keyword', 'create')
            ->notEmpty('keyword');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->integer('status')
            ->allowEmpty('status');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentPages'));
        return $rules;
    }
    public function get_slug($page = ''){
        return Inflector::slug(strtolower($page));
    }
}
