<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Page Entity.
 *
 * @property int $id
 * @property int $parent_id
 * @property \App\Model\Entity\ParentPage $parent_page
 * @property string $page_key
 * @property string $page_headline
 * @property string $page_title
 * @property string $content
 * @property string $keyword
 * @property string $description
 * @property int $status
 * @property \Cake\I18n\Time $created
 * @property \App\Model\Entity\ChildPage[] $child_pages
 */
class Page extends Entity
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
