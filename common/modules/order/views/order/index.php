<?php

use common\modules\order\models\Order;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\order\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Buyurtmalar';
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
<?= \soft\grid\GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => false
    ],
    'columns' => [
//        'id',
        [
            'attribute' => 'user_id',
            'format' => 'raw',
            'value' => function (Order $model) {
                return $model->user ? $model->user->getFullname() : '';
            }
        ],
        'price:sum',
        [
            'attribute' => 'type_id',
            'format' => 'raw',
            'filter' => Order::tariffCourseBooktypes(),
            'value' => function (Order $model) {
                return $model->getTariffCourseBookTypeName();
            }
        ],
        [
            'attribute' => 'video_id',
            'format' => 'raw',
            'value' => function (Order $model) {
                return $model->video ? $model->video->name : '';
            }
        ],
        [
            'attribute' => 'tariff_id',
            'format' => 'raw',
            'value' => function (Order $model) {
                return $model->tariff ? $model->tariff->name : '';
            }
        ],
        [
            'attribute' => 'book_id',
            'format' => 'raw',
            'value' => function (Order $model) {
                return $model->book ? $model->book->name : '';
            }
        ],
        [
            'attribute' => 'payment_type_id',
            'format' => 'raw',
            'filter' => Order::types(),
            'value' => function (Order $model) {
                return $model->getTypeName();
            }
        ],
        'status',
        //'created_by',
        //'updated_by',
        'created_at',
        //'updated_at',
        'actionColumn' => [
            "template" => '{view}',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
    