<?php

use common\modules\product\models\Category;
use common\modules\product\StatusActiveColumn;
use soft\grid\StatusColumn;
use soft\grid\ViewLinkColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\product\models\search\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
<?= \soft\grid\GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            /** @see soft\widget\button\Button for other configurations */
            'modal' => true,
        ]
    ],
    'columns' => [
        [
            'class' => ViewLinkColumn::class,
            'attribute' => 'name',
        ],
        [
            'class' => StatusColumn::class
        ],
        'actionColumn' => [
            'template' => '{view} {update} {delete} {active} ',
            'buttons' => [

                'active' => function ($url, $model, $key) {
                    return StatusActiveColumn::getStatuses($model, 'category');
                }
            ],
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
    