<?php

use common\modules\userBalance\models\UserTariff;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\userBalance\models\search\UserTariffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Foydalanuvchi obunalari';
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
<?= $this->render('_info_box_tariff') ?>

<?= \soft\grid\GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
//    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            /** @see soft\widget\button\Button for other configurations */
            'modal' => true,
        ]
    ],
    'columns' => [
        'user.fullname',
        'tariff.name',
        'price:sum',
        'started_at:date',
        'expired_at:date',
        [
            'attribute' => 'payment_type_id',
            'format' => 'raw',
            'value' => function (UserTariff $model) {
                return $model->getTypeName();
            }
        ],
        //'expired_at',
        //'order_id',
        //'status',
        //'created_by',
        //'updated_by',
        //'created_at',
        //'updated_at',
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
    