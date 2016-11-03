<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SystemMail Entity.
 *
 * @property int $id
 * @property string $email_type
 * @property string $sender_name
 * @property string $sender_email
 * @property string $title
 * @property string $subject
 * @property string $message
 * @property \Cake\I18n\Time $adddate
 */
class SystemMail extends Entity
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
