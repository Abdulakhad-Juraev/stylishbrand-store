<?php


/* @var $this soft\web\View */
/* @var $model common\modules\video\models\BeforeAfterCourse */

$this->title = Yii::t('site', 'Create a new');
$this->params['breadcrumbs'][] = ['label' => 'Before After Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
