<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
/**
 * RateRecord Entity.
 *
 * @property int $id
 * @property int $rate_id
 * @property \App\Model\Entity\Rate $rate
 * @property bool $status
 * @property \Cake\I18n\Time $from_date
 * @property \Cake\I18n\Time $to_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 */
class RateRecord extends Entity
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
