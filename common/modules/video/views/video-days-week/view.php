<?php


/* @var $this soft\web\View */
/* @var $model common\modules\video\models\VideoDaysWeek */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Video Days Weeks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= \soft\widget\bs4\DetailView::widget([
        'model' => $model,
        'attributes' => [
              'id', 
              'week_id', 
              'video_id', 
              'created_by', 
              'updated_by', 
              'created_at', 
              'updated_at', 
'created_at',
'createdBy.fullname',
'updated_at',
'updatedBy.fullname'        ],
    ]) ?>
