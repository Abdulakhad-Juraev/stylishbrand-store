<?php


/* @var $this soft\web\View */
/* @var $model common\modules\order\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_tab-menu', ['model' => $model]) ?>
<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
          'attribute'=>'order_type',
          'value'=>function ($model) {
              return $model->orderTypeName ?? '';
          }
        ],
        [
            'attribute'=>'payment_type',
            'value'=>function ($model) {
                return $model->typeName ?? '';
            }
        ],
        'total_price:sum',
        'statusBadge:raw',
         ],
]) ?>
