<?php


/* @var $this soft\web\View */

/* @var $model common\modules\product\models\ProductCharacter */

use soft\widget\bs4\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Characters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'title',
        [
            'attribute' => 'category_character_id',
            'value' => function ($model) {
                return $model->categoryCharacter->name ?? '';
            }
        ],
        [
            'attribute' => 'product_id',
            'value' => function ($model) {
                return $model->product->name ?? '';
            }
        ],
        'statusBadge:raw',
        'with_check_icon',
        'created_at',
        'createdBy.fullname',
        'updated_at',
        'updatedBy.fullname'],
]) ?>
