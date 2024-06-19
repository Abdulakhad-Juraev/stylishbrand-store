<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 12.11.2021, 9:23
 */

namespace soft\grid;

use soft\helpers\Html;

class ViewLinkColumn extends DataColumn
{


    public $format = 'raw';

    public $linkOptions = [];

    public $pjax = false;

    public $action = 'view';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);


        if (!isset($this->linkOptions['data-pjax'])) {

            if ($this->pjax === false) {
                $this->pjax = '0';
            }

            $this->linkOptions['data-pjax'] = $this->pjax;

        }

        return Html::a($value, [$this->action, 'id' => $key], $this->linkOptions);

    }

}
