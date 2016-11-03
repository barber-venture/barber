<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProjectDocument Entity.
 *
 * @property int $id
 * @property int $project_id
 * @property \App\Model\Entity\Project $project
 * @property string $counter_signed_assessment
 * @property string $counter_signed_assessment_by_customer
 * @property string $customer_cancellation_form
 * @property string $notice_to_proceed
 * @property string $signed_work_contract
 * @property string $signed_work_contract_modified
 * @property string $permit
 * @property string $permission_to_operate
 * @property string $certificate_of_completion
 * @property string $change_order
 * @property string $conter_signed_addendum
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class ProjectDocument extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
