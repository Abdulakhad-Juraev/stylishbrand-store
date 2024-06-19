<?php


/* @var $this soft\web\View */
/* @var $model common\models\AppVersion */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'App Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
//        'id',
        'apk_version',
        'apk_url',
        'ios_version',
        'ios_url',
        'status',
//        'created_by',
//        'updated_by',
//        'created_at',
//        'updated_at',
        'created_at',
        'createdBy.fullname',
        'updated_at',
        'updatedBy.fullname'
    ],
]) ?>
