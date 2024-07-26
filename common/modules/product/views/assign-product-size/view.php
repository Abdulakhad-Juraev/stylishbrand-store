<?php


/* @var $this soft\web\View */

/* @var $model common\modules\product\models\AssignProductSize */

use common\modules\product\models\AssignProductSize;
use soft\widget\bs4\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assign Product Sizes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
            'attribute' => 'product_id',
            'value' => $model->products->name ?? '',
        ],
        [
            'attribute' => 'size_id',
            'value' => $model->sizes->name ?? '',
        ],
        'statusBadge:raw',
        ],
]) ?>
