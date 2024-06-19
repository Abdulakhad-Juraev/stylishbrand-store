<?php

namespace soft\behaviors;

use yii\db\BaseActiveRecord;
use yii\web\UploadedFile;

class UploadBehavior extends \mohorev\file\UploadBehavior
{

    /**
     * @var boolean|callable If `true`, old file will be deleted after model updating.
     * Usage:
     *      unlinkOnSave => function($model){
     *          return ... ;
     *      }
     */
    public $unlinkOnSave = true;

    /**
     * @var boolean|callable If `true` current attribute file will be deleted after model deletion.
     * Usage:
     *      unlinkOnDelete => function($model){
     *          return ... ;
     *      }
     */
    public $unlinkOnDelete = true;

    public function beforeSave()
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        if (in_array($model->scenario, $this->scenarios)) {
            if ($this->file instanceof UploadedFile) {
                if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
                    if ($this->unlinkOnSave() === true) {
                        $this->delete($this->attribute, true);
                    }
                }
                $model->setAttribute($this->attribute, $this->file->name);
            } else {
                // Protect attribute
                unset($model->{$this->attribute});
            }
        } else {
            if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
                if ($this->unlinkOnSave() === true) {
                    $this->delete($this->attribute, true);
                }
            }
        }
    }

    /**
     * This method is invoked after deleting a record.
     */
    public function afterDelete()
    {
        $attribute = $this->attribute;
        if ($this->unlinkOnDelete() && $attribute) {
            $this->delete($attribute);
        }
    }

    /**
     * @return bool Check if unlink old file on save
     */
    protected function unlinkOnSave()
    {
        if (is_callable($this->unlinkOnSave)){
            return call_user_func($this->unlinkOnSave, $this->owner);
        }
        return $this->unlinkOnSave;
    }

    /**
     * @return bool Check if unlink old file after delete
     */
    protected function unlinkOnDelete()
    {
        if (is_callable($this->unlinkOnDelete)){
            return call_user_func($this->unlinkOnDelete, $this->owner);
        }
        return $this->unlinkOnDelete;
    }

}
