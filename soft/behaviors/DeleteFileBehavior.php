<?php


namespace soft\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class DeleteFileBehavior
 * $model ni o'chirgandan keyin shu $model ga tegishli bo'lgan fayl(lar)ni o'chirish uchun mo'ljallangan behavior
 * @package soft\behaviors
 */
class DeleteFileBehavior extends Behavior
{

    /**
     * @var string|array fayl adresi qiymatiga ega bo'lgan attribut yoki attributlar ro'yxati
     */
    public $attributes;

    /**
     * @var string fayl joylashgan asos papka
     */
    public $basePath = '@frontend/web';

    /**
     * @inheritDoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * Delete files
     */
    public function afterDelete()
    {

        $basePath = Yii::getAlias($this->basePath);
        $attributes = (array) $this->attributes;

        foreach ($attributes as $attribute){

            $filename = $basePath . $this->owner->$attribute;
            if (is_file($filename)){
                unlink($filename);
            }

        }

    }

}