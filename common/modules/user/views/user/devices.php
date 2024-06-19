<?php

/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 18.05.2022, 10:25
 */

/* @var $this View */
/* @var $searchModel UserDeviceSearch */
/* @var $dataProvider ActiveDataProvider */

/* @var $model User */

use common\modules\user\columns\UserDeviceActionColumn;
use common\modules\user\models\search\UserDeviceSearch;
use common\modules\user\models\User;
use common\modules\user\models\UserDevice;
use soft\grid\GridView;
use soft\grid\StatusColumn;
use soft\web\View;
use yii\data\ActiveDataProvider;

$this->title = "Foyd.chi qurilmalari " . $model->fullname;
$this->addBreadCrumb("Foydalanuvchilar", 'index');
$this->addBreadCrumb($model->fullname, ['user/view', 'id' => $model->id]);
$this->addBreadCrumb("Qurilmalar");

?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>

<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{refresh}',
    'toolbarButtons' => [
        'create' => false
    ],
    'columns' => [
        'device_id',
        'device_name',
        [
            'class' => StatusColumn::class,
            'filter' => UserDevice::statuses(),
        ],
//        'token',
        [
            'attribute' => 'created_at',
            'format' => 'datetimeuz',
            'filter' => false,
        ],
        [
            'class' => UserDeviceActionColumn::class,
        ]
    ],
]); ?>
