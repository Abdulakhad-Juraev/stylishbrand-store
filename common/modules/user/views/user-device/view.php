<?php


/* @var $this soft\web\View */
/* @var $model common\modules\user\models\UserDevice */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'user.fullname',
        'device_id',
        'device_name',
        'device_token',
        'token',
        'statusBadge:raw',
        'created_at',
        'updated_at',
    ],
]) ?>
