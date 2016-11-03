<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MasterRateRecord Entity.
 *
 * @property int $id
 * @property int $master_rate_id
 * @property \App\Model\Entity\MasterRate $master_rate
 * @property \Cake\I18n\Time $from_date
 * @property \Cake\I18n\Time $to_date
 * @property float $rate
 * @property int $status
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class MasterRateRecord extends Entity
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
