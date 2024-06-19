<?php
/*
* @author Muhammadjonov Ulug'bek <muhammadjonovulugbek98@gmail.com>
* @link telegram: https://t.me/U_Muhammadjonov
* @date 01.12.2022, 12:53
*/


use common\models\User;
use soft\grid\GridView;


/* @var $this soft\web\View */
/* @var $searchModel common\modules\userbalance\models\search\UserTariffSearch */
/* @var $model User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->getFullname() . ' - Sotib olgan filmlari';
$this->addBreadCrumb("Foydalanuvchilar", 'index');
$this->addBreadCrumb($model->fullname, ['user/view', 'id' => $model->id]);
$this->addBreadCrumb('Sotib olgan filmlari');
?>
<?= $this->render('_tab-menu', ['model' => $model]) ?>
<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{refresh}',
//    'panel' => [
//        'before' => "<i>Foydalanuvchi <b>{$model->fullname}</b> tomonidan sotib olingan tariflar</i>",
//    ],
    'toolbarButtons' => [
        'create' => false,
    ],
    'columns' => [
        [
            'attribute' => 'film.name',
            'label' => 'Film nomi'
        ],
        'price:sum',
        'created_at',
        //'updated_at',
        'actionColumn' => [
            'controller' => '/user-balance-manager/order',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
