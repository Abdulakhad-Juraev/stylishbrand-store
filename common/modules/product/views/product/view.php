<?php


/* @var $this soft\web\View */
/* @var $model common\modules\product\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>


<?= $this->render('_tab-menu', ['model' => $model]) ?>
    <?= \soft\widget\bs4\DetailView::widget([
        'model' => $model,
        'attributes' => [
              'id', 
              'slug', 
              'image', 
              'price:sum',
              'category_id',
              'sub_category_id', 
              'percentage', 
              'published_at', 
              'expired_at', 
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
