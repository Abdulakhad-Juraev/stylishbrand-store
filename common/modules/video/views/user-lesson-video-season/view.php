<?php


/* @var $this soft\web\View */
/* @var $model common\modules\video\models\UserLessonVideoSeason */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Lesson Video Seasons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'parent_video_id',
        'user_id',
        'season_id',
        'copmleted_count',
        'lesson_count',
        'completed_percent',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'created_at',
        'createdBy.fullname',
        'updated_at',
        'updatedBy.fullname'],
]) ?>
