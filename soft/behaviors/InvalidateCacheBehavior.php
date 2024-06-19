<?php


namespace soft\behaviors;

use Yii;
use yii\base\Behavior;
use yii\caching\TagDependency;
use yii\db\BaseActiveRecord;

class InvalidateCacheBehavior extends Behavior
{

    public $tags;


    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'invalidateCache',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'invalidateCache',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'invalidateCache',
        ];
    }

    public function invalidateCache()
    {
        TagDependency::invalidate(Yii::$app->cache, $this->tags);
    }

}
