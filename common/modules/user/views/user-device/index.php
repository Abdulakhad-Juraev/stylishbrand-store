<?php

use common\models\User;
use common\modules\user\models\UserDevice;

/* @var $this soft\web\View */
/* @var $model User */
/* @var $searchModel common\modules\user\models\search\UserDeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Qurilmalar';
$this->params['breadcrumbs'][] = ['label' => 'Foydalanuvchilar', 'url' => ['user/index']];
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
//        'user_id',
        'device_id',
        'device_name',
//        'status',
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
