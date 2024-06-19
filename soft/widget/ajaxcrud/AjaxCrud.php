<?php

namespace soft\widget\ajaxcrud;

use soft\helpers\Url;
use Yii;
use soft\db\ActiveRecord;
use soft\helpers\Html;
use yii\base\Component;
use yii\web\Response;

/**
 * @property-read \yii\base\Controller|\yii\web\Controller|\yii\console\Controller $controller
 * @property-read bool $isAjax
 */
class AjaxCrud extends Component
{

    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_VIEW = 'view';
    const ACTION_DELETE = 'delete';

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $view;

    /**
     * @var array
     */
    public $viewParams = [];

    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $saveLabel;

    /**
     * @var string
     */
    public $closeLabel;

    /**
     * @var bool
     */
    public $forceClose = true;

    /**
     * @var string
     */
    public $forceReload = '#crud-datatable-pjax';

    /**
     * @var string
     */
    public $returnUrl;

    /**
     * @var string
     */
    public $updateUrl;

    /**
     * @var string
     * @see ACTION_* constants
     */
    public $action;

    /**
     * @var string
     */
    public $footer;

    /**
     * @var callable a PHP callable that will be called before model validate (after model load).
     * Usage:
     * ```php
     *      function ($model, AjaxCrud $widget) {
     *          $model->field1 = 1;
     *      }
     * ```
     */
    public $beforeValidate;

    /**
     * @var callable a PHP callable that will be called after model validate (before model save).
     * Usage:
     * ```php
     *      function ($model, AjaxCrud $widget) {
     *          $model->field1 = 1;
     *      }
     * ```
     */
    public $afterValidate;

    /**
     * @return array|string
     */
    public function __toString()
    {
        if ($this->action == self::ACTION_VIEW) {
            return $this->viewAction();
        }
        if ($this->action == self::ACTION_CREATE) {
            return $this->createAction();
        }
        if ($this->action == self::ACTION_UPDATE) {
            return $this->updateAction();
        }

        if ($this->action == self::ACTION_DELETE) {
            return $this->deleteAction();
        }
        return '';

    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if ($this->isAjax) {
            $this->formatJson();
        }

        if ($this->closeLabel === null) {
            $this->closeLabel = Yii::t('site', 'Close');
        }
        if ($this->saveLabel === null) {
            $this->saveLabel = Yii::t('site', 'Save');
        }

        if ($this->view === null) {
            $this->view = $this->action;
        }

        if ($this->title === null) {
            if ($this->action == self::ACTION_CREATE) {
                $this->title = Yii::t('site', 'Create a new');
            }
            if ($this->action == self::ACTION_UPDATE) {
                $this->title = Yii::t('site', 'Update');
            }
        }
    }

    /**
     * @return bool
     */
    public function getIsAjax()
    {
        return Yii::$app->request->isAjax;
    }

    /**
     * Set the response format to JSON
     */
    public function formatJson()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    /**
     * @return \yii\base\Controller|\yii\console\Controller|\yii\web\Controller
     */
    public function getController()
    {
        return Yii::$app->controller;
    }

    /**
     * @param string[] $options
     * @return string
     */
    public function closeButton($options = [])
    {

        if (!isset($options['class'])) {
            $options['class'] = 'btn btn-default pull-right';
        }

        $options['data-dismiss'] = 'modal';
        return Html::button($this->closeLabel, $options);
    }

    /**
     * @param string[] $options
     * @return string
     */
    public function updateButton($options = [])
    {

        if ($this->updateUrl === null) {
            if ($this->model == null) {
                return '';
            }
            $this->updateUrl = Url::to(['update', 'id' => $this->model->id]);
        }

        if (!isset($options['class'])) {
            $options['class'] = 'btn btn-primary pull-right';
        }

        $options['role'] = 'modal-remote';

        return Html::a(Yii::t('site', 'Update'), $this->updateUrl, $options);
    }

    /**
     * @return string
     */
    public function viewFooter()
    {
        return $this->updateButton() . $this->closeButton();
    }

    /**
     * @param string[] $options
     * @return string
     */
    public function saveButton($options = [])
    {

        if (!isset($options['class'])) {
            $options['class'] = 'btn btn-primary';
        }

        $options['type'] = 'submit';
        return Html::button($this->saveLabel, $options);
    }

    /**
     * @return string
     */
    public function modalFormFooter()
    {
        return $this->saveButton() . $this->closeButton();
    }

    /**
     * @return array
     */
    public function closeModal()
    {
        $this->formatJson();
        $options['forceClose'] = $this->forceClose;
        if ($this->forceReload != false) {
            $options['forceReload'] = $this->forceReload;
        }
        return $options;
    }

    /**
     * @return array
     */
    public function closeModalResponse()
    {
        if ($this->isAjax) {
            return $this->closeModal();
        } else {
            if ($this->returnUrl == null) {
                $this->returnUrl = ['index', 'id' => Yii::$app->request->get('id')];
            }
            return $this->controller->redirect($this->returnUrl);
        }
    }

    /**
     * @return array|string
     * @throws \Exception
     */
    public function viewAction()
    {
        $view = $this->view ?? self::ACTION_VIEW;
        $viewParams = $this->viewParams;
        $model = $this->model;
        $viewParams['model'] = $this->model;

        if ($this->isAjax) {

            $title = $this->title;
            if ($title == null) {
                $title = Yii::t('site', 'View') . "#" . $model->id;
            }

            $content = $this->content;
            if ($content == null) {
                $content = $this->controller->renderAjax($view, $viewParams);
            }

            $footer = $this->footer;

            if ($footer == null) {
                $footer = $this->viewFooter();
            }

            $result = [
                'title' => $title,
                'content' => $content,
                'footer' => $footer,
            ];

            if ($this->forceClose) {
                $result['forceReload'] = $this->forceReload;
            }

            $this->formatJson();
            return $result;

        } else {
            return $this->controller->render($view, $viewParams);
        }
    }

    /**
     * @return array|string|\yii\web\Response
     * @throws \Exception
     */
    public function createAction()
    {

        if ($this->title == null) {
            $this->title = Yii::t('site', 'Create a new');
        }
        if ($this->view == null) {
            $this->view = self::ACTION_CREATE;
        }
        return $this->action();
    }

    /**
     * @return array|string|\yii\web\Response
     * @throws \Exception
     */
    public function updateAction()
    {

        if ($this->title == null) {
            $this->title = Yii::t('site', 'Update');
        }
        if ($this->view == null) {
            $this->view = self::ACTION_UPDATE;
        }
        return $this->action();
    }

    /**
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteAction()
    {
        $this->model->delete();
        return $this->closeModalResponse();
    }

    /**
     * @return array|string|\yii\web\Response
     * @throws \Exception
     */
    public function action()
    {

        $request = Yii::$app->request;

        if ($this->isAjax) {

            if ($this->model->load($request->post()) && $this->modelSave()) {

                if ($this->forceClose) {
                    return $this->closeModal();
                } else {
                    return $this->viewAction();
                }

            } else {
                return $this->actionModal();
            }

        } else {

            if ($this->model->load($request->post()) && $this->modelSave()) {

                if ($this->returnUrl == null) {
                    $this->returnUrl = ['view', 'id' => $this->model->id];
                }
                return $this->controller->redirect($this->returnUrl);
            }

            $viewParams = $this->viewParams;
            $viewParams['model'] = $this->model;
            return $this->controller->render($this->view, $viewParams);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function modelSave()
    {

        if (is_callable($this->beforeValidate) && call_user_func($this->beforeValidate, $this->model, $this) === false) {
            return false;
        }

        if ($this->model->validate()) {
            if (is_callable($this->afterValidate) && call_user_func($this->afterValidate, $this->model, $this) === false) {
                return false;
            }

            return $this->model->save(false);
        }
        return false;
    }

    /**
     * @return array
     */
    public function actionModal()
    {
        $model = $this->model;
        $viewParams = $this->viewParams;
        $view = $this->view;
        $viewParams['model'] = $model;

        $this->formatJson();

        return [
            'title' => $this->title,
            'content' => $this->content ?? $this->controller->renderAjax($view, $viewParams),
            'footer' => $this->footer ?? $this->modalFormFooter(),
        ];
    }

    /**
     * @return array|string
     */
    public function renderView()
    {

        $viewParams = $this->viewParams;
        $viewParams['model'] = $this->model;

        if ($this->footer == null) {
            $this->footer = $this->action == 'view' ? $this->viewFooter() : $this->modalFormFooter();
        }

        if ($this->isAjax) {
            return [
                'title' => $this->title,
                'content' => $this->content ?? $this->controller->renderAjax($this->view, $viewParams),
                'footer' => $this->footer,
            ];
        }

        return $this->controller->render($this->view, $viewParams);
    }

    /**
     * For debugging purposes
     * @param $content string
     * @return array
     */
    public function debugModal($content)
    {
        $this->formatJson();

        return [
            'title' => "Debug",
            'content' => $content,
            'footer' => $this->closeButton()
        ];
    }

}
