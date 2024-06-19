<?php


/* @var $this soft\web\View */
/* @var $model common\modules\video\models\UserLessonVideoSeason */

$this->title = Yii::t('site', 'Create a new');
$this->params['breadcrumbs'][] = ['label' => 'User Lesson Video Seasons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
