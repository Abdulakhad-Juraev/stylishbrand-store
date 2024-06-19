<?php

use common\modules\user\models\User;
use common\modules\user\models\UserPayment;
use soft\grid\GridView;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\user\models\search\UserPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Foydalanuchi to\'lovlari';
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
//        'create' => [
//            /** @see soft\widget\button\Button for other configurations */
//            'modal' => true,
//        ]
        'create' => false
    ],
    'columns' => [
        [
            'attribute' => 'user_id',
            'format' => 'raw',
//            'filter' => User::map(),
            'value' => function (UserPayment $model) {
                return $model->user ? $model->user->fullname : '';
            }
        ],
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
        [
            'attribute' => 'amount',
            'value' => function (UserPayment $model) {
                return as_sum($model->amount);
            },
        ],


        'created_at',
        'actionColumn' => [
            'template' => '{view}',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
    