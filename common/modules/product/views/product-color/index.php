<?php

use soft\grid\GridView;
use soft\grid\StatusColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\product\models\search\ProductColorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ranglar');
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            'modal' => false,
        ]
    ],
    'columns' => [
        'name',
        [
            'attribute' => 'color',
            'format' => 'raw',
            'value' => function ($model) {
                return "<div style='width: 30px; height: 20px; background-color: {$model->color};'></div>";
            },
        ],
        ['class' => StatusColumn::class],
        'actionColumn' => [
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
//            'updateOptions' => [
//                'role' => 'modal-remote',
//            ],
        ],
    ],
]); ?>
    