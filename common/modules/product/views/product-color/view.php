<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\ProductColor */

use soft\widget\bs4\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Colors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
              'id', 
              'name',
//              'color',
              'statusBadge:raw',
'created_at',
'createdBy.fullname',
'updated_at',
'updatedBy.fullname'        ],
    ]) ?>
