<?php

namespace soft\actions\base;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\ForbiddenHttpException;

class CreateBaseAction extends SoftBaseAction
{

    /**
     * @var string view file path
     */
    public $view = 'create';

    /**
     * @var bool|callable check if the action is allowed
     */
    public $checkAccess = true;

    /**
     * @var \soft\db\ActiveRecord
     */
    public $model;

    /**
     * @throws \yii\web\ForbiddenHttpException
     */
    public function checkAccess()
    {
        if ($this->checkAccess !== null) {

            $checkAccess = $this->checkAccess;

            if (is_callable($checkAccess)) {
                $checkAccess = call_user_func($this->checkAccess);
            }

            if (!$checkAccess) {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
    }

    /**
     * Creates a new model.
     * @return \soft\db\ActiveRecord the model newly created
     */
    public function createModel()
    {
        return new $this->modelClass;
    }


}
