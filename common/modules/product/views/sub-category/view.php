<?php


/* @var $this soft\web\View */

/* @var $model common\modules\product\models\SubCategory */

use common\modules\product\models\SubCategory;
use soft\widget\bs4\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sub Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        [
            'attribute' => 'category_id',
            'format' => 'raw',
            'value' => function (SubCategory $model) {
                return $model->category->name ?? '';
            }
        ],
        'statusBadge:raw',
        'created_at',
        'createdBy.fullname',
        'updated_at',
        'updatedBy.fullname'],
]) ?>
