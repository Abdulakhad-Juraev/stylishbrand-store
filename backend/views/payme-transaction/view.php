<?php


/* @var $this soft\web\View */
/* @var $model common\models\PaymeTransaction */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payme Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= \soft\widget\bs4\DetailView::widget([
        'model' => $model,
        'attributes' => [
              'id', 
              'paycom_transaction_id', 
              'paycom_time', 
              'paycom_time_datetime', 
              'create_time', 
              'perform_time', 
              'cancel_time', 
              'amount', 
              'state', 
              'reason', 
              'receivers', 
              'order_id', 
              'time', 
'created_at',
'createdBy.fullname',
'updated_at',
'updatedBy.fullname'        ],
    ]) ?>
