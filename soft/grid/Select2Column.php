<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 25.10.2021, 8:44
 */

namespace soft\grid;

use soft\helpers\ArrayHelper;

class Select2Column extends DataColumn
{

    public $filterType = '\kartik\select2\Select2';

    public $data;

    public $allowClear = true;

    public $placeholder = 'Танланг...';

    public $pluginOptions = [];

    public function init()
    {
        $this->normalizeOptions();
        parent::init();
    }

    private function normalizeOptions()
    {
        $options = $this->generateOptions();
        $this->filterWidgetOptions = ArrayHelper::merge($options, $this->filterWidgetOptions);
    }

    private function generateOptions()
    {
        return [
            'data' => (array)$this->data,
            'pluginOptions' => [
                'allowClear' => $this->allowClear,
                'placeholder' => $this->placeholder,
            ]
        ];
    }


}
