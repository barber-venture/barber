<?php
namespace App\Model\Table;

use App\Model\Entity\SystemMail;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SystemMails Model
 *
 */
class SystemMailsTable extends Table
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

        $this->table('system_mails');
        $this->displayField('title');
        $this->primaryKey('id');
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
            ->requirePresence('email_type', 'create')
            ->notEmpty('email_type');

        $validator
            ->requirePresence('sender_name', 'create')
            ->notEmpty('sender_name');

        $validator
            ->requirePresence('sender_email', 'create')
            ->notEmpty('sender_email');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('subject', 'create')
            ->notEmpty('subject');

        $validator
            ->requirePresence('message', 'create')
            ->notEmpty('message');

        $validator
            ->date('adddate')
            ->requirePresence('adddate', 'create')
            ->notEmpty('adddate');

        return $validator;
    }
}
