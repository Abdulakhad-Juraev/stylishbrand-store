<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 11.01.2022, 14:24
 */

namespace soft\grid;

class DateRangeColumn extends DataColumn
{

    public $attribute = 'date';

    public $filterType = '\soft\widget\kartik\DateRangePicker';

    public $format = 'date';

    public $filterWidgetOptions = [
        'initDefaultRangeExpr' => true,
    ];

}
