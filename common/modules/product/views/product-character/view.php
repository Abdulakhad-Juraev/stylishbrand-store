<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\ProductCharacter */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Characters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= \soft\widget\bs4\DetailView::widget([
        'model' => $model,
        'attributes' => [
              'id', 
              'title', 
              'category_character_id', 
              'status', 
              'created_by', 
              'updated_by', 
              'created_at', 
              'updated_at', 
'created_at',
'createdBy.fullname',
'updated_at',
'updatedBy.fullname'        ],
    ]) ?>
