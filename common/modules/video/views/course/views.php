<?php
/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 13.05.2022, 9:29
 */

use common\modules\video\models\Video;
use soft\grid\GridView;
use soft\web\View;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */
/* @var $model Video */

$this->registerAjaxCrudAssets();

$this->title = 'Ko\'rganlar. ' . $model->fullName;

$this->addBreadCrumb('Videolar', ['course/index']);
$this->addBreadCrumb($model->fullName, ['course/view', 'id' => $model->id]);
$this->addBreadCrumb($this->title);

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
            'attribute' => 'user_id',
            'label' => 'Foydalanuvchi',
            'format' => 'raw',
            'value' => function (LastSeenFilm $model) {
                return Html::a($model->user ? $model->user->fullname : '', ['/user-manager/user/views', 'id' => $model->user_id], ['data-pjax' => '0']);
            }
        ],
//        'created_at',
        //'updated_at',
    ],
]); ?>
