<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SlydepayOrder Entity
 *
 * @property int $id
 * @property string $payment_common_id
 * @property string $payment_token
 * @property int $plan_id
 * @property int $user_id
 * @property int $order_status
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 *
 * @property \App\Model\Entity\PaymentCommon $payment_common
 * @property \App\Model\Entity\Plan $plan
 * @property \App\Model\Entity\User $user
 */
class SlydepayOrder extends Entity
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
        'id' => false
    ];
}
