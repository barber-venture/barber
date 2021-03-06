<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Visiter Entity.
 *
 * @property int $id
 * @property int $user_id
 * @property \App\Model\Entity\User $user
 * @property int $to_user_id
 * @property \App\Model\Entity\ToUser $to_user
 * @property int $count
 * @property \Cake\I18n\Time $updated
 */
class Visiter extends Entity
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
