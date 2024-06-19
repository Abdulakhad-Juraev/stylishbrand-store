<?php

use common\models\User;
use common\modules\userbalance\models\UserTariff;
use soft\grid\GridView;
use soft\helpers\Url;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\userbalance\models\search\UserTariffSearch */
/* @var $model User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->getFullname() . ' - Sotib olgan obunalar';
$this->addBreadCrumb("Foydalanuvchilar", 'index');
$this->addBreadCrumb($model->fullname, ['user/view', 'id' => $model->id]);
$this->addBreadCrumb('Sotib olgan obunalari');

?>
<?= $this->render('_tab-menu', ['model' => $model]) ?>

<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create} {refresh}',
    'panel' => [
        'before' => "<i>Foydalanuvchi <b>{$model->fullname}</b> tomonidan sotib olingan obunalar</i>",
    ],
    'toolbarButtons' => [
        'create' => [
            'modal' => true,
            'url' => Url::to(['/userbalance-manager/user-tariff/create', 'id' => $model->id])
        ],
    ],
    'columns' => [
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
        'created_at',
        //'updated_at',
        'actionColumn' => [
            'controller' => '/userbalance-manager/user-tariff',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
