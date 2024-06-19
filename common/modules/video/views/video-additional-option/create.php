<?php


/* @var $this soft\web\View */
/* @var $model common\modules\video\models\VideoAdditionalOption */

$this->title = Yii::t('site', 'Create a new');
$this->params['breadcrumbs'][] = ['label' => 'Video Additional Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
