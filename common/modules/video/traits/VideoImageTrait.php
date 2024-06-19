<?php

namespace common\modules\video\traits;

use mohorev\file\UploadImageBehavior;
use soft\helpers\Url;

/**
 * @property UploadImageBehavior $imageBehavior
 * @property string $imageUrl
 * @property string $previewImageUrl
 * @property string $thumbImageUrl
 */
trait VideoImageTrait
{

    /**
     * @return UploadImageBehavior
     */
    public function getImageBehavior()
    {
        return $this->getBehavior('image');
    }

    /**
     * Preview rasm url
     * @return string|null
     */
    public function getPreviewImageUrl()
    {
        return $this->getImageUrl('preview');
    }

    /**
     * Thumb rasm url
     * @return string|null
     */
    public function getThumbImageUrl()
    {
        return $this->getImageUrl('thumb');
    }

    /**
     * @param $version string
     * @return string
     */
    public function getImageUrl(string $version = 'thumb')
    {
        return Url::withHostInfo($this->getImageBehavior()->getThumbUploadUrl('image', $version) ?? self::defaultImage());
    }

    /**
     * @return string
     */
    public static function defaultImage(): string
    {
        return '/images/podcast_default.png';
    }

}
