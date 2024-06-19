<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 28-Mar-24, 12:06
 */

namespace common\modules\video\behaviors;

use common\modules\video\models\Video;
use soft\behaviors\CyrillicSlugBehavior;

class VideoSluggableBehavior extends CyrillicSlugBehavior
{
    /**
     * @var Video
     */
    public $owner;

    public $attribute = 'name';

    public $addParentSlug = true;

    /**
     * @param $event \yii\base\ModelEvent
     * @return string
     */
    protected function getValue($event)
    {
        if ($this->attribute !== null) {
            if ($this->isNewSlugNeeded()) {
                $slug = $this->make($this->owner->{$this->attribute});
            } else {
                return $this->owner->{$this->slugAttribute};
            }
        } else {
            $slug = parent::getValue($event);
        }

        $slug = $this->normalize($slug);

        return $this->ensureUnique ? $this->makeUnique($slug) : $slug;
    }

    /**
     * If film is part of series, then parent slug is added to child slug
     * @param string $slug
     * @return string
     */
    private function normalize(string $slug)
    {
        $owner = $this->owner;
        if ($this->addParentSlug && $owner->getIsPartial() && $owner->parent) {
            $slug = $owner->parent->slug . '-' . $slug;
        }
        return $slug;

    }
}