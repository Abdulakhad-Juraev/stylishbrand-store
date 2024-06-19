<?php


namespace common\modules\video\behaviors;

use common\modules\video\models\VideoSeason;
use soft\behaviors\CyrillicSlugBehavior;
use yii\base\ModelEvent;

class VideoSeasonSluggableBehavior extends CyrillicSlugBehavior
{

    /**
     * @var VideoSeason
     */
    public $owner;

    public $attribute = 'name';

    public $addParentSlug = true;

    /**
     * @param $event ModelEvent
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
        if ($this->addParentSlug && $owner->video) {
            $slug = $owner->video->slug . '-' . $slug;
        }
        return $slug;

    }

}
