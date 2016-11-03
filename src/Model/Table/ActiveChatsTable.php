<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 */
class ActiveChatsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('active_chats');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'to_user_id',
            'joinType' => 'INNER'
        ]);
        
        
        $this->belongsTo('FromUser', [
            'className' => 'Users', 
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('ToUser', [
            'className' => 'Users', 
            'foreignKey' => 'to_user_id',
            'joinType' => 'INNER'
        ]);
        
        /*
        $this->hasMany('ChatMessages', [
            'foreignKey' => false,
            'joinType' => 'INNER',
            'conditions' => ['ActiveChats.to_user_id' => 'ChatMessages.user_id'],
            //['OR' => [['ActiveChats.to_user_id' => 'ChatMessages.to_user_id'],
            //                          ['ActiveChats.user_id' => 'ChatMessages.user_id']]],
            'order' => 'created desc'
        ]);
        */
        
    }

}
