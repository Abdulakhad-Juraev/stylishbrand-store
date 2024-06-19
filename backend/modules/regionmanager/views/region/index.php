<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 13.07.2021, 15:18
 */

use backend\modules\regionmanager\models\Region;

/* @var $this soft\web\View */
/* @var $searchModel backend\modules\regionmanager\models\search\RegionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Viloyatlar';
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
<?= \soft\grid\GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'panel' => [
        'before' => 'Viloyatlar ro`yxati',
    ],
    'toolbarTemplate' => false,
    'columns' => [
//        [
//            'attribute' => 'name_uz',
//            'format' => 'raw',
//            'value' => function ($model) {
//                /** @var Region $model */
//                return a($model->name, ['district/index', 'region_id' => $model->id], ['data-pjax' => 0]);
//            }
//        ],
            'name_uz',
//        'name_oz',
        'name_ru',
        'actionColumn' => [
            'template' => '{update}',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
    