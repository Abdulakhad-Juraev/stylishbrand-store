<?php


/* @var $this soft\web\View */

/* @var $model common\modules\user\models\UserPayment */

use common\modules\user\models\User;
use common\modules\user\models\UserPayment;
use common\modules\video\models\Video;
use soft\widget\bs4\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Foydalanuchi to\'lovlari', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
            'attribute' => 'user_id',
            'format' => 'raw',
            'filter' => User::map(),
            'value' => function (UserPayment $model) {
                return $model->user ? $model->user->fullname : '';
            }
        ],
        'amount',
        [
            'attribute' => 'payment_type_id',
            'format' => 'raw',
            'value' => function (UserPayment $model) {
                return $model->getTypeName();
            }
        ],
        [
            'attribute' => 'type_id',
            'format' => 'raw',
            'value' => function (UserPayment $model) {
                return $model->tariffCourseBooktypes() ? $model->getTariffCourseBookTypeName() : '';
            }
        ],
        'created_at',
        'createdBy.fullname',
        'updated_at',
        'updatedBy.fullname'],
]) ?>
