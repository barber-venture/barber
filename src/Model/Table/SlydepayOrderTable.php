<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SlydepayOrder Model
 *
 * @property \Cake\ORM\Association\BelongsTo $PaymentCommons
 * @property \Cake\ORM\Association\BelongsTo $Plans
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\SlydepayOrder get($primaryKey, $options = [])
 * @method \App\Model\Entity\SlydepayOrder newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SlydepayOrder[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SlydepayOrder|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SlydepayOrder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SlydepayOrder[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SlydepayOrder findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SlydepayOrderTable extends Table
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

        $this->table('slydepay_order');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
 
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
            ->requirePresence('payment_token', 'create')
            ->notEmpty('payment_token');

        $validator
            ->integer('order_status')
            ->requirePresence('order_status', 'create')
            ->notEmpty('order_status');

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
         

        return $rules;
    }
}
