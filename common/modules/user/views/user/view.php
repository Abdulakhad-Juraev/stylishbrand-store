<?php


/* @var $this soft\web\View */

/* @var $model common\modules\user\models\User */

use common\modules\user\models\User;

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Foydalanuvchilar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'panel' => $this->isAjax ? false : [],
    'toolbar' => $model->getIsDeleted() ? [
        'buttons' => [
            'delete' => !$model->getIsDeleted(),
            'update' => !$model->getIsDeleted(),
        ]
    ] : [],
    'attributes' => [
        [
            'attribute' => 'imageUrl',
            'label' => Yii::t('app', 'Image'),
            'format' => ['image', ['width' => '60px']],
        ],
        'username',
        'firstname',
        'lastname',
        'devicesCount',
        [
            'attribute' => 'status',
            'filter' => User::statuses(),
            'format' => 'raw',
            'value' => function ($model) {
                /** @var User $model */
                return $model->statusBadge;
            }
        ],

//        [
//            'attribute' => 'is_blocked',
//            'format' => 'raw',
//            'value' => function (User $model) {
//                return $model->getBlockName();
//            },
//        ],
        'created_at',
        'updated_at',
        'deleted_at:datetime',
        [
            'attribute' => 'deleted_by',
            'value' => function (User $model) {
                return $model->deletedBy ? $model->deletedBy->fullname : '-';
            }
        ],
        'activeDevicesCount',
    ],
]) ?>
