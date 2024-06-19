<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 24.07.2021, 10:06
 */

namespace soft\widget\kartik;

use soft\helpers\ArrayHelper;
use yii\base\InvalidArgumentException;
use yii\helpers\Html;

class DatePicker extends \kartik\widgets\DatePicker
{

    const FORMAT = 'd.m.Y';
    public $placeholder = 'KK.MM.YYYY';

    public $autoclose = true;
    public $todayHighlight = true;

    public $language = 'ru';

    public function init()
    {
        $this->normalizeValues();
        $this->normalizeAttributes();
        $this->registerCss();
        parent::init();
    }

    private function normalizeValues()
    {
        if ($this->hasModel()) {

            if (!preg_match(Html::$attributeRegex, $this->attribute, $matches)) {
                throw new InvalidArgumentException('Attribute name must contain word characters only.');
            }

            $attribute = $matches[2];
            $value = $this->value ?? $this->model->$attribute;

            if (!empty($value) && is_integer($value)) {
                $this->value = date(self::FORMAT, $value);
                $this->model->$attribute = date(self::FORMAT, $value);
            }

        } else {

            $value = $this->value;
            if (!empty($value) && is_integer($value)) {
                $this->value = date(self::FORMAT, $value);
            }

        }

    }

    private function registerCss()
    {
        $css = <<<CSS
        .datepicker-dropdown{
            z-index: 9999!important;
        }
CSS;

        $this->view->registerCss($css, [], 'datepicker-dropdown-css');
    }

    /**
     * @return array
     */
    private function defaultPluginOptions()
    {
        return [
            'autoclose' => $this->autoclose,
            'todayHighlight' => $this->todayHighlight,
        ];
    }

    /**
     * @return array
     */
    private function defaultOptions()
    {
        return [
            'placeholder' => $this->placeholder,
        ];
    }

    private function normalizeAttributes()
    {
        $this->pluginOptions = ArrayHelper::merge($this->pluginOptions, $this->defaultPluginOptions());
        $this->options = ArrayHelper::merge($this->options, $this->defaultOptions());
    }
}
