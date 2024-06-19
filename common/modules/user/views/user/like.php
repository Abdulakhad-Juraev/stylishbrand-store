<?php
/*
* @author Muhammadjonov Ulug'bek <muhammadjonovulugbek98@gmail.com>
* @link telegram: https://t.me/U_Muhammadjonov
* @date 01.12.2022, 14:08
*/

use common\models\User;
use common\modules\film\models\LikeDislike;
use soft\grid\GridView;


/* @var $this soft\web\View */
/* @var $model User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->getFullname() . ' - like bosgan filmlari';
$this->addBreadCrumb("Foydalanuvchilar", 'index');
$this->addBreadCrumb($model->fullname, ['user/view', 'id' => $model->id]);
$this->addBreadCrumb('Like bosgan filmlari');
?>
<?= $this->render('_tab-menu', ['model' => $model]) ?>
<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'toolbarTemplate' => '{refresh}',
//    'panel' => [
//        'before' => "<i>Foydalanuvchi <b>{$model->fullname}</b> tomonidan sotib olingan tariflar</i>",
//    ],
    'toolbarButtons' => [
        'create' => false,
    ],
    'columns' => [
        [
            'attribute' => 'film_id',
            'label' => 'Film nomi',
            'value' => function (LikeDislike $model) {
                return $model->film ? $model->film->name : '';
            }
        ],
        'created_at',
        //'updated_at',
    ],
]); ?>
