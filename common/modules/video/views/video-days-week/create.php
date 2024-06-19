<?php


/* @var $this soft\web\View */
/* @var $model common\modules\video\models\VideoDaysWeek */

$this->title = Yii::t('site', 'Create a new');
$this->params['breadcrumbs'][] = ['label' => 'Video Days Weeks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
