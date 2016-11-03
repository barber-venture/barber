<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerDetail Entity.
 *
 * @property int $id
 * @property int $project_id
 * @property \App\Model\Entity\Project $project
 * @property string $address
 * @property int $unit
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $property_type
 * @property string $property_ownership
 * @property string $fo_first_name
 * @property string $fo_last_name
 * @property string $fo_suffix
 * @property string $fo_ssn
 * @property string $fo_dob
 * @property string $fo_email
 * @property string $fo_street_address
 * @property string $fo_unit
 * @property string $fo_city
 * @property string $fo_state
 * @property int $fo_zip
 * @property string $so_first_name
 * @property string $so_last_name
 * @property string $so_suffix
 * @property string $so_ssn
 * @property string $so_dob
 * @property string $so_email
 * @property string $so_street_address
 * @property string $so_unit
 * @property string $so_city
 * @property string $so_state
 * @property int $so_zip
 * @property string $fo_same_property
 * @property string $so_same_property
 */
class CustomerDetail extends Entity
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
