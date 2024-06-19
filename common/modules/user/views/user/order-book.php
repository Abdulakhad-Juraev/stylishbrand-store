<?php

use common\models\User;
use common\modules\order\models\Order;
use common\modules\order\models\search\OrderSearch;
use soft\grid\GridView;

/* @var $model User */
/* @var $searchModel OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Kitob buyurtma. " . $model->fullname;
$this->addBreadCrumb("Foydalanuvchilar", 'index');
$this->addBreadCrumb($model->fullname, ['user/view', 'id' => $model->id]);
$this->addBreadCrumb("Kitob buyurtma");

$this->registerAjaxCrudAssets();
?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>


<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{refresh}',
    'panel' => [
        'before' => "<i>Foydalanuvchi <b>{$model->fullname}</b> tomonidan buyurtma qilingan kitoblar</i>",
    ],
    'toolbarButtons' => [
        'create' => [
            /** @see soft\widget\button\Button for other configurations */
            'modal' => false,
        ]
    ],
    'columns' => [

        [
            'attribute' => 'book_id',
            'format' => 'raw',
            'value' => function (Order $model) {
                return $model->book ? $model->book->name : '';
            }
        ],
        'price:sum',
        [
            'attribute' => 'payment_type_id',
            'format' => 'raw',
            'filter' => Order::types(),
            'value' => function (Order $model) {
                return $model->types() ? $model->getTypeName() : '';
            }
        ],

    ],
]); ?>

