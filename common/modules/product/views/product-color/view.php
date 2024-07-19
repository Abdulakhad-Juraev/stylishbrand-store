<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\ProductColor */

use soft\widget\bs4\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ranglar'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
              'id', 
              'name',
            [
                'attribute' => 'color',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<div style='width: 20px; height: 20px; background-color: {$model->color};'></div>";
                },
            ],
              'statusBadge:raw',
        ],
    ]) ?>
