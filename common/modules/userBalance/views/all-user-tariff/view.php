<?php


/* @var $this soft\web\View */
/* @var $model common\modules\userBalance\models\UserTariff */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Tariffs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
//        'id',
//        'user_id',
        'tariff.name',
        'price:sum',
        'started_at:date',
        'expired_at:date',
        'order_id',
//        'status',
//        'created_by',
//        'updated_by',
//        'created_at',
//        'updated_at',
        'created_at',
        'createdBy.fullname',
        'updated_at',
        'updatedBy.fullname'
    ],
]) ?>
