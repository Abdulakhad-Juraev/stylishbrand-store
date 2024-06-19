<?php

namespace soft\actions\base;

use soft\helpers\ArrayHelper;
use soft\widget\ajaxcrud\AjaxCrud;
use Yii;
use yii\base\Action;
use yii\base\InvalidArgumentException;

/**
 *
 * @property AjaxCrud $ajaxCrud
 */
class SoftBaseAction extends Action
{

    /**
     * @var string model class name
     */
    public $modelClass;

    /**
     * @var string url to return.
     */
    public $returnUrl;

    private $_ajaxCrud;

    /**
     * @return \yii\web\Response
     */
    public function back()
    {
        return $this->ajaxCrud->closeModalResponse($this->returnUrl);
    }

    /**
     * @return object|AjaxCrud
     * @throws yii\base\InvalidConfigException
     */
    public function getAjaxCrud()
    {
        if ($this->_ajaxCrud === null) {
            $this->setAjaxCrud([]);
        }
        return $this->_ajaxCrud;
    }

    /**
     * Sets the ajaxCrud component for this controller
     * @param $value mixed
     * @throws yii\base\InvalidConfigException
     */
    public function setAjaxCrud($value)
    {
        if (is_array($value)) {
            $class = ArrayHelper::remove($value, 'class', AjaxCrud::class);
            $config = ['class' => $class];
            $this->_ajaxCrud = Yii::createObject(array_merge($config, $value));
        } elseif ($value instanceof AjaxCrud) {
            $this->_ajaxCrud = $value;
        } else {
            throw new InvalidArgumentException('Only AjaxCrud instance or configuration array is allowed.');
        }
    }

}
