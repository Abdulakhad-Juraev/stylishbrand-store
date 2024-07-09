<?php

use yii\helpers\Html;

/* @var $this soft\web\View */
/* @var $model common\modules\product\models\ProductCharacter */

$this->title = Yii::t('site', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Characters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>

