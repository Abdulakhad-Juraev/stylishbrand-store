<?php


/* @var $this soft\web\View */

/* @var $model common\modules\userBalance\models\PurchaseViaAdmin */

use common\models\User;
use common\modules\userBalance\models\PurchaseViaAdmin;
use soft\widget\bs4\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Adminlar orqali xarid qilish', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'full_name',
        'phone',
        [
            'attribute' => 'type_id',
            'format' => 'raw',
            'value' => function (PurchaseViaAdmin $model) {
                return $model->tariffCourseBooktypes() ? $model->getTariffCourseBookTypeName() : '';
            }
        ],

        'statusBadge:raw',
        'created_at',
        'createdBy.fullname',
    ],
]) ?>
