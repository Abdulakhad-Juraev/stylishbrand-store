<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 27.12.2021, 10:07
 */

namespace soft\actions\base;

use Yii;
use yii\base\Action;
use soft\helpers\ArrayHelper;
use soft\components\AjaxCrud;
use yii\web\ForbiddenHttpException;
use yii\base\InvalidArgumentException;
use yii\web\Response;

/**
 * Base class for view, update, delete or such kind of actions
 */
class ModelBaseAction extends SoftBaseAction
{

    /**
     * @var int|string model id qiymati. agar berilmasa get so'rovdan oladi
     */
    public $modelId;

    /**
     * @var bool|callable
     *
     * usage:
     * ```php function($model){
     *      return $model->user_id == Yii::$app->user->getId();
     *    }
     * ```
     */
    public $checkAccess;

    /**
     * @var callable|\soft\db\ActiveRecord|\yii\db\ActiveRecord. If not set, will automatically set according to `modelClass` and `id` properties
     *
     * usage as callback function:
     * ```php
     *   function(){
     *       return SomeModel::findOne(Yii::$app->request->get('id'));
     *  }
     */
    public $model;

    /**
     * Agar `lesson` qiymati berilmagan bo'lsa, lesson qiymatini topish uchun
     * shu get so'rovi orqali kelayotgan ushbu parametrdan foydalaniladi
     * @var string
     */
    public $requestParam = 'id';

    /**
     * @var string path to view file
     */
    public $view;

    /**
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {

        if ($this->model == null) {

            if ($this->modelClass == null) {
                throw new InvalidArgumentException('`model` or `modelClass` property must be set');
            }

            $class = $this->modelClass;

            if ($this->modelId == null) {
                $this->modelId = \Yii::$app->request->get($this->requestParam);
            }

            /** @var \soft\db\ActiveRecord $class static class name */
            $model = $class::findOne($this->modelId);
            $this->model = $model;

        } else {
            if (is_callable($this->model)) {
                $this->model = call_user_func($this->model);
            }
        }

        if ($this->model == null) {
            not_found();
        }

        $this->checkAccess();

    }

    public function checkAccess()
    {
        if ($this->checkAccess !== null) {

            $checkAccess = $this->checkAccess;

            if (is_callable($checkAccess)) {
                $checkAccess = call_user_func($this->checkAccess, $this->model);
            }

            if (!$checkAccess) {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
    }

}
