<?php


/* @var $this soft\web\View */
/* @var $model common\modules\order\models\OrderItem */

use common\modules\order\models\OrderItem;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
            'attribute' => 'product_id',
            'value' => function (OrderItem $model) {
                   return $model->product->name ?? '';
            },
        ],
        'count',
        'price',
        'total_price',
       ],
]) ?>
