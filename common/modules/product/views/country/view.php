<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\Country */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        'status',
        'created_at',
        'createdBy.fullname',
        'updated_at',
        'updatedBy.fullname'],
]) ?>
