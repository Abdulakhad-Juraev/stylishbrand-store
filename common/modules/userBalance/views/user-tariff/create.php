<?php


/* @var $this soft\web\View */
/* @var $model common\modules\userBalance\models\UserTariff */

$this->title = Yii::t('site', 'Create a new');
$this->params['breadcrumbs'][] = ['label' => 'User Tariffs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
