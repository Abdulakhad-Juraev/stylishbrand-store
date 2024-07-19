<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\CategoryCharacter */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kategoriya xususiyatlari'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        ['attribute' => 'category_id',
            'value' => function ($model) {
                return $model->category->name ?? '';
            }
        ],
        'statusBadge:raw',
    ],
]) ?>
