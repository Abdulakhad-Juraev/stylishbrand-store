<?php

namespace common\modules\video\behaviors;

use common\modules\video\models\Video;
use soft\db\ActiveRecord;
use Yii;
use yii\base\Behavior;
use yii\web\ServerErrorHttpException;

class VideoBehavior extends Behavior
{

    /**
     * @var Video
     */
    public $owner;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * @throws \yii\web\ServerErrorHttpException
     */
    public function beforeDelete()
    {
        try {
            $this->owner->deleteFullOrgSrc();
            $this->owner->deleteFullStream();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException("Videoni o'chirishda xatolik yuz berdi\n" . $e->getMessage());
        }

    }

}
