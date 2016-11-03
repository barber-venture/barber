<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserDetail Entity.
 *
 * @property int $id
 * @property int $user_id
 * @property \App\Model\Entity\User $user
 * @property int $country_id
 * @property \App\Model\Entity\Country $country
 * @property int $city_id
 * @property \App\Model\Entity\City $city
 * @property int $state_id
 * @property \App\Model\Entity\State $state
 * @property string $address1
 * @property string $address2
 * @property string $nike_name
 * @property \Cake\I18n\Time $dob
 * @property bool $gender
 * @property string $phone
 * @property string $mobile
 * @property string $profile_image
 * @property string $benar_image
 * @property string $about_me
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 */
class UserDetail extends Entity
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
