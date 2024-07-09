<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\ProductCharacter */

$this->title = Yii::t('site', 'Create a new');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Characters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
