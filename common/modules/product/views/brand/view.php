<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\Brand */

use soft\widget\bs4\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brands'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        'statusBadge:raw',
    ],
]) ?>
