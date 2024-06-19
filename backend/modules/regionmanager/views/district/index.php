<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 13.07.2021, 15:18
 */

use backend\modules\regionmanager\models\District;
use soft\helpers\Html;

/* @var $this soft\web\View */
/* @var $region \backend\modules\regionmanager\models\Region */
/* @var $searchModel backend\modules\regionmanager\models\search\DistrictSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $region->name. ' - Tuman va shaharlar';
$this->addBreadCrumb('Viloyatlar', ['region/index']);
$this->params['breadcrumbs'][] = $region->name;
$this->params['breadcrumbs'][] = 'Tuman va shaharlar';
$this->registerAjaxCrudAssets();
?>
    <?= \soft\grid\GridView::widget([
        'id' => 'crud-datatable',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel, 
//        'toolbarTemplate' => '{create}{refresh}',
        'toolbarTemplate' => false,
//        'toolbarButtons' => [
//            'create' => [
//                /** @see soft\widget\button\Button for other configurations */
//                'modal' => true,
//            ]
//        ],
        'bulkButtonsTemplate' => '{delete}',
        'bulkButtons' => [
            'delete' => [
                /** @see soft\widget\button\BulkButton for other configurations */
            ],
        ],
        'cols' => [
            [
                'attribute' => 'name_uz',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var District $model */
                    return a($model->name, ['quarter/index', 'district_id' => $model->id], ['data-pjax' => 0]);
                }
            ],
            'actionColumn' => [
                'viewOptions' => [
                    'role' => 'modal-remote',
                ],
                'updateOptions' => [
                    'role' => 'modal-remote',
                ],
            ],
        ],
        ]); ?>
    