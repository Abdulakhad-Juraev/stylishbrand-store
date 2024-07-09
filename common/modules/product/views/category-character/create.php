<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\CategoryCharacter */

$this->title = Yii::t('site', 'Create a new');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Category Characters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
