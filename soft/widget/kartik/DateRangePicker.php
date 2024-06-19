<?php


namespace soft\widget\kartik;

use soft\helpers\Html;
use Yii;
use soft\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\web\JsExpression;

class DateRangePicker extends \kartik\daterange\DateRangePicker
{

    const SEPARATOR = ' - ';

    public $timePicker = false;

    public $convertFormat = true;

    public $useWithAddon = true;

    public $separator = self::SEPARATOR;

    public $format = 'd.m.Y';

    public $timeFormat = 'd.m.Y H:i';

    public $drops = 'up';

    public $initDefaultRangeExpr = false;

    public $addon;

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        if ($this->addon === null) {
            if ($this->isBs(4)) {
                $this->addon = <<< HTML
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
    </div>
HTML;
            }
        }
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function run()
    {

        $defaultPluginOptions = [
            'locale' => [
                'cancelLabel' => Yii::t('site', 'Cancel'),
                'applyLabel' => Yii::t('site', 'Confirm'),
                'format' => $this->format,
                'separator' => $this->separator,
            ],
        ];

        if ($this->timePicker) {

            $defaultPluginOptions['timePicker'] = true;
            $defaultPluginOptions['timePicker24Hour'] = true;
            $defaultPluginOptions['timePickerIncrement'] = 5;
            $defaultPluginOptions['locale']['format'] = $this->timeFormat;
            $defaultPluginOptions['locale']['customRangeLabel'] = "Boshqa oraliq";

        }
        $this->pluginOptions = ArrayHelper::merge($defaultPluginOptions, $this->pluginOptions);

        $this->normalizeValues();
        $this->initSettings();
        $input = $this->renderInput();
        echo Html::tag('div', $this->addon . $input, ['class' => 'input-group']);

    }

    /**
     * @throws \Exception
     */
    private function normalizeValues()
    {

        $locale = ArrayHelper::getValue($this->pluginOptions, 'locale', []);
        $format = ArrayHelper::getValue($locale, 'format', $this->format);
        $separator = ArrayHelper::getValue($locale, 'separator', $this->separator);

        if ($this->hasModel()) {

            if (empty($this->value)) {

                if (!empty($this->startAttribute) && !empty($this->endAttribute)) {

                    $beginValue = $this->model->{$this->startAttribute};
                    $endValue = $this->model->{$this->endAttribute};

                    if (!empty($beginValue) && is_integer($beginValue) && !empty($endValue) && is_integer($endValue)) {
                        $this->value = date($format, $beginValue) . $separator . date($format, $endValue);
                    }
                }
            }
        }
    }

    /**
     * Initializes the pluginOptions range list
     * @throws InvalidConfigException
     */
    protected function initRange()
    {
        if (isset($dummyValidation)) {
            /** @noinspection PhpUnusedLocalVariableInspection */
            $msg = 'Oraliqni tanlang';
        }

        if ($this->initDefaultRangeExpr) {
            $this->pluginOptions['ranges'] = $this->initDefaultRanges();
        }

        if ($this->presetDropdown) {
            $m = 'moment()';
            $this->initRangeExpr = $this->hideInput = true;
            $this->pluginOptions['opens'] = \yii\helpers\ArrayHelper::getValue($this->pluginOptions, 'opens', 'left');

            ########### Custom code here ##############

            if (!isset($this->pluginOptions['ranges'])) {
                $this->pluginOptions['ranges'] = $this->initDefaultRanges();
            }
            ########### End Custom code ##############

            if (empty($this->value)) {
                $this->pluginOptions['startDate'] = new JsExpression("{$m}.startOf('day')");
                $this->pluginOptions['endDate'] = new JsExpression($m);
            }
        }
        $opts = $this->pluginOptions;
        if (!$this->initRangeExpr || empty($opts['ranges']) || !is_array($opts['ranges'])) {
            return;
        }
        $range = [];
        foreach ($opts['ranges'] as $key => $value) {
            if (!is_array($value) || empty($value[0]) || empty($value[1])) {
                throw new InvalidConfigException(
                    "Invalid settings for pluginOptions['ranges']. Each range value must be a two element array."
                );
            }
            $range[$key] = [static::parseJsExpr($value[0]), static::parseJsExpr($value[1])];
        }
        $this->pluginOptions['ranges'] = $range;
    }


    public function initDefaultRanges()
    {

        $m = 'moment()';
        $beg = "{$m}.startOf('day')";
        $end = "{$m}.endOf('day')";
        $last = "{$m}.subtract(1, 'month')";
        $ranges = [
            'Bugun' => [$beg, $end],
            'Kecha' => ["{$beg}.subtract(1,'days')", "{$end}.subtract(1,'days')"],
        ];

        if ($this->includeDaysFilter) {
            foreach ($this->presetFilterDays as $n) {
                $from = $n - 1;
                $ranges += [
                    "Oxirgi {$n} kun" => ["{$beg}.subtract({$from}, 'days')", $end],
                ];
            }
        }
        $ranges += [
            'Shu oy' => ["{$m}.startOf('month')", "{$m}.endOf('month')"],
            "O'tgan oy" => ["{$last}.startOf('month')", "{$last}.endOf('month')"],
        ];
        if ($this->includeMonthsFilter) {
            foreach ($this->presetFilterMonths as $n) {
                $from = $n - 1;
                $beg = "{$m}.subtract({$from}, 'month').startOf('month')";
                $end = "{$m}.endOf('month')";
                $ranges += [
                    "Oxirgi $n oy" => ["{$beg}", "{$end}"],
                ];
            }
        }

        return $ranges;
    }

}
