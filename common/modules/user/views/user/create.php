<?php


/* @var $this soft\web\View */
/* @var $model common\modules\user\models\User */

$this->title = "Yangi qo'shish";
$this->params['breadcrumbs'][] = ['label' => 'Foydalanuvchilar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
