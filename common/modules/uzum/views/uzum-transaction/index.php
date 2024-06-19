<?php

use common\modules\uzum\models\UzumTransaction;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\uzum\models\search\UzumTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Uzum Transactions';
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
<?= \soft\grid\GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => false
    ],
    'columns' => [
//        'id',
        'order_id',
        'amount',
        'timestamp',
        'serviceId',
        'transId',
        'status',
        'transTime',
        'confirmTime',
        'reverseTime',
        //'created_by',
        //'updated_by',
        //'created_at',
        //'updated_at',
        'actionColumn' => [
            'template' => '{view}',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
    