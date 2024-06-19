<?php


/* @var $this soft\web\View */
/* @var $model common\modules\uzum\models\UzumTransaction */

$this->title = Yii::t('site', 'Create a new');
$this->params['breadcrumbs'][] = ['label' => 'Uzum Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
