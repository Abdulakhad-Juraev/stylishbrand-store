<?php


/* @var $this soft\web\View */
/* @var $model common\modules\user\models\UserPayment */

$this->title = Yii::t('site', 'Create a new');
$this->params['breadcrumbs'][] = ['label' => 'Foydalanuchi to\'lovlari', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
