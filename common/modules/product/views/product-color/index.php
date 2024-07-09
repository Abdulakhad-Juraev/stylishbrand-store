<?php

use soft\grid\GridView;
use soft\grid\StatusColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\product\models\search\ProductColorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Product Colors');
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
            'modal' => true,
        ]
    ],
    'columns' => [
        'name',
//        'color',
        ['class' => StatusColumn::class],
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
    