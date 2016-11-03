<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SiteSetting Entity.
 *
 * @property int $id
 * @property string $site_name
 * @property string $site_title
 * @property string $site_logo
 * @property int $per_page_limit
 * @property string $site_hotline_no
 * @property string $site_address
 * @property string $favicon
 * @property bool $is_online
 * @property string $contact_us_email
 * @property string $info_email
 * @property string $no_reply_email
 * @property string $lat
 * @property string $lng
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 */
class SiteSetting extends Entity
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
