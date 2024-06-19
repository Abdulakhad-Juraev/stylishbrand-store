<?php

namespace soft\grid;

use soft\base\BootstrapTypeInterface;
use soft\helpers\BootstrapHelper;
use soft\helpers\Html;

class SerialColumn extends \kartik\grid\SerialColumn implements BootstrapTypeInterface
{

    /**
     * @var string|callable
     */
    public $type;

    /**
     * @inheritdoc
     */
    public function renderDataCell($model, $key, $index)
    {
        $options = $this->fetchContentOptions($model, $key, $index);
        $this->parseExcelFormats($options, $model, $key, $index);
        $out = $this->grid->formatter->format($this->renderDataCellContent($model, $key, $index), $this->format);
        return \yii\helpers\Html::tag('td', $out, $options);
    }

    /**
     * @param $model
     * @param $key
     * @param $index
     * @return array
     */
    protected function fetchContentOptions($model, $key, $index)
    {

        $options = parent::fetchContentOptions($model, $key, $index);

        $type = $this->type;
        if (is_callable($type)) {
            $type = call_user_func($type, $model, $key, $index);
        }
        if ($type) {
            $isBs3 = BootstrapHelper::isBs3();
            $prefix = $isBs3 ? 'alert alert' : 'bg';
            $class = $prefix . '-' . $type;

            Html::addCssClass($options, ['type' => $class]);

            if ($isBs3) {
                Html::addCssStyle($options, ['border-radius' => '0px']);
            }
        }

        return $options;
    }
}
