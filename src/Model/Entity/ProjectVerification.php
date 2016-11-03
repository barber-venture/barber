<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProjectVerification Entity.
 *
 * @property int $id
 * @property int $project_id
 * @property float $zillow_estimate
 * @property string $all_property_owners
 * @property string $all_debt_secured
 * @property string $property_taxes
 * @property string $subject_property
 * @property string $bankruptcies
 * @property float $outstanding_mortgage
 * @property float $loan_value
 * @property float $annual_tax_payment
 * @property bool $is_approved
 * @property \Cake\I18n\Time $created
 */
class ProjectVerification extends Entity
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
