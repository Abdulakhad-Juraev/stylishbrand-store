<?php


use common\modules\order\models\Order;
use common\modules\order\models\OrderItem;
use common\modules\order\models\search\OrderItemSearch;
use soft\grid\GridView;
use soft\grid\StatusColumn;


/* @var $model Order */
/* @var $this soft\web\View */
/* @var $searchModel OrderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Category ' . $model->id;
$this->addBreadCrumb('Category', ['category/index']);
$this->addBreadCrumb($model->id, ['category/view', 'id' => $model->id]);
$this->addBreadCrumb('Sub Category');
$this->registerAjaxCrudAssets();


?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>

<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            'modal' => true,
            'url' => ['order-item/create', 'order_id' => $model->id],
        ]
    ],
    'columns' => [
        'order_id',
        [
            'attribute' => 'product_id',
            'value' => function (OrderItem $model) {
                return $model->product->name ?? '';
            },
        ],
        'count',
        'price',
        'total_price',
        'actionColumn' => [
            'controller' => 'order-item',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>