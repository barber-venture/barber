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
 * @property string $user_code
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $activation_key
 * @property bool $status
 * @property bool $is_verify
 * @property bool $user_type
 * @property \Cake\I18n\Time $date_password
 * @property bool $is_online
 * @property bool $is_discovery
 * @property int $change_password_count
 * @property bool $deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 * @property \App\Model\Entity\AlbumImage[] $album_images
 * @property \App\Model\Entity\Album[] $albums
 * @property \App\Model\Entity\DislikeUser[] $dislike_users
 * @property \App\Model\Entity\Favorite[] $favorites
 * @property \App\Model\Entity\SiteLog[] $site_logs
 * @property \App\Model\Entity\UserDetail[] $user_details
 * @property \App\Model\Entity\UserPlanAssociation[] $user_plan_associations
 * @property \App\Model\Entity\UserSocialLink[] $user_social_link
 * @property \App\Model\Entity\UserTagAssociation[] $user_tag_associations
 * @property \App\Model\Entity\Visiter[] $visiters
 */
class User extends Entity {

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

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    protected function _setPassword($password) {
        if ($password != '' && strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }

}
