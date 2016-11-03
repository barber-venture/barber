<?php
namespace App\Model\Table;

use App\Model\Entity\SiteSetting;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SiteSettings Model
 *
 */
class SiteSettingsTable extends Table
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

        $this->table('site_settings');
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
            ->requirePresence('site_name', 'create')
            ->notEmpty('site_name');

        $validator
            ->requirePresence('site_title', 'create')
            ->notEmpty('site_title');

        $validator
            ->requirePresence('site_logo', 'create')
            ->notEmpty('site_logo');

        $validator
            ->integer('per_page_limit')
            ->requirePresence('per_page_limit', 'create')
            ->notEmpty('per_page_limit');

        $validator
            ->requirePresence('site_hotline_no', 'create')
            ->notEmpty('site_hotline_no');

        $validator
            ->requirePresence('site_address', 'create')
            ->notEmpty('site_address');

        $validator
            ->requirePresence('favicon', 'create')
            ->notEmpty('favicon');

        $validator
           
            ->requirePresence('is_online', 'create')
            ->notEmpty('is_online');

        $validator
            ->requirePresence('contact_us_email', 'create')
            ->notEmpty('contact_us_email');

        $validator
            ->requirePresence('info_email', 'create')
            ->notEmpty('info_email');

        $validator
            ->requirePresence('no_reply_email', 'create')
            ->notEmpty('no_reply_email');

        $validator
            ->requirePresence('lat', 'create')
            ->notEmpty('lat');

        $validator
            ->requirePresence('lng', 'create')
            ->notEmpty('lng');

        return $validator;
    }
}
