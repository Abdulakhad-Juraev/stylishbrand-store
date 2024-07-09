<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\Country */

$this->title = Yii::t('site', 'Create a new');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
