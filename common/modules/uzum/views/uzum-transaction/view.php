<?php


/* @var $this soft\web\View */
/* @var $model common\modules\uzum\models\UzumTransaction */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Uzum Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= \soft\widget\bs4\DetailView::widget([
        'model' => $model,
        'attributes' => [
              'id', 
              'order_id', 
              'amount', 
              'timestamp', 
              'serviceId', 
              'transId', 
              'status', 
              'transTime', 
              'confirmTime', 
              'reverseTime', 
              'created_by', 
              'updated_by', 
              'created_at', 
              'updated_at', 
'created_at',
'createdBy.fullname',
'updated_at',
'updatedBy.fullname'        ],
    ]) ?>
