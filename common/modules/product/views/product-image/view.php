<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\ProductImage */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'imageUrl',
            'label' => "Rasm",
            'format' => ['image', ['width' => '40px']]
        ],
        [
            'attribute' => 'color_id',
            'value' => function ($model)  {
                    return $model->color->name ?? '';
            }
        ],
        [
            'attribute' => 'product_id',
            'value' => function ($model)  {
                    return $model->product->name ?? '';
            }
        ],
        'statusBadge:raw',
         ],
]) ?>
