<?php
namespace App\Model\Table;

use App\Model\Entity\AdminQuestion;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdminQuestions Model
 *
 */
class AdminQuestionsTable extends Table
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

        $this->table('admin_questions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        
        //$this->hasMany('AdminQuestionOptions', [
        //    'foreignKey' => 'admin_question_id'
        //]);
        
        $this->hasOne('UserAnswers',[
            'foreignKey' => 'question_id'                        
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
            ->requirePresence('question', 'create')
            ->notEmpty('question');

        return $validator;
    }
}
