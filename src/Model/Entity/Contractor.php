<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

use Cake\Auth\DefaultPasswordHasher;
/**
 * User Entity.
 *
 * @property int $id
 * @property int $role_id
 * @property \App\Model\Entity\Role $role
 * @property int $country_id
 * @property string $title
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $mobile
 * @property string $activation_key
 * @property bool $status
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 */
class Contractor extends Entity
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
