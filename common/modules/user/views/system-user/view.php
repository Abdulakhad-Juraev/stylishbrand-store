<?php


/* @var $this soft\web\View */

/* @var $model common\modules\user\models\User */

use common\modules\user\models\User;

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Foydalanuvchilar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'panel' => $this->isAjax ? false : [],
    'attributes' => [
        'username',
        'firstname',
        'lastname',
        [
            'attribute' => 'image',
            'label' => Yii::t('app', 'Image'),
            'format' => ['image', ['width' => '90px']],
        ],
        [
            'attribute' => 'status',
            'filter' => User::statuses(),
            'format' => 'raw',
            'value' => function ($model) {
                /** @var User $model */
                return $model->statusBadge;
            }
        ],
        'created_at',
        'updated_at',
    ],
]) ?>
