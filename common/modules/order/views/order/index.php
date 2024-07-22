<?php

use common\models\User;
use common\modules\order\models\Order;
use soft\grid\GridView;
use soft\grid\StatusColumn;
use soft\grid\ViewLinkColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\order\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
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
        [
            'class' => ViewLinkColumn::class,
            'attribute' => 'fullname',
        ],
        'phone',
//        [
//            'class' => ViewLinkColumn::class,
//            'attribute' => 'user_id',
//            'filter' => User::map(),
//            'value' => function (Order $model) {
//                return $model->user->username ?? '';
//            }
//        ],
        [
            'attribute' => 'order_type',
            'filter' => Order::orderTypes(),
            'value' => function (Order $model) {
                return $model->getOrderTypeName() ?? '';
            }
        ],
        [
            'attribute' => 'payment_type',
            'filter' => Order::types(),
            'value' => function (Order $model) {
                return $model->getTypeName() ?? '';
            }
        ],
        'total_price:sum',
//        [
//            'class' => StatusColumn::class
//        ],

        'actionColumn' => [
//            'viewOptions' => [
//                'role' => 'modal-remote',
//            ],
//            'updateOptions' => [
//                'role' => 'modal-remote',
//            ],
        ],
    ],
]); ?>
    