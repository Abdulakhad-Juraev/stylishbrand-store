<?php

use common\models\User;
use common\modules\user\models\UserPayment;
use soft\grid\GridView;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\user\models\search\UserPaymentSearch */
/* @var $model User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Foyd.chi to'lovlari. " . $model->fullname;
$this->addBreadCrumb("Foydalanuvchilar", 'index');
$this->addBreadCrumb($model->fullname, ['user/view', 'id' => $model->id]);
$this->addBreadCrumb("To'lovlari");


$this->registerAjaxCrudAssets();

?>
<?= $this->render('_tab-menu', ['model' => $model]) ?>
<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'panel' => [
        'before' => "<i>Foydalanuvchi <b>{$model->fullname}</b> tomonidan to'langan to'lovlar</i>",
    ],
    'toolbarButtons' => [
//        'create' => [
//            /** @see soft\widget\button\Button for other configurations */
//            'modal' => true,
//            'url' => ['/user-manager/detail-user-payment/create', ' user_id' => $model->id],
//        ]
        'create' => false
    ],
    'columns' => [

        'amount:sum',
        [
            'attribute' => 'payment_type_id',
            'format' => 'raw',
            'filter' => UserPayment::types(),
            'value' => function (UserPayment $model) {
                return $model->types() ? $model->getTypeName() : '';
            }
        ],
        [
            'attribute' => 'type_id',
            'format' => 'raw',
            'filter' => UserPayment::tariffCourseBooktypes(),
            'value' => function (UserPayment $model) {
                return $model->tariffCourseBooktypes() ? $model->getTariffCourseBookTypeName() : '';
            }
        ],
        'created_at',
        'actionColumn' => [
            'template' => '{view}',
            'controller' => '/user-manager/detail-user-payment',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],

    ],
]); ?>
