<?php

use common\models\PaymeTransaction;

/* @var $this soft\web\View */
/* @var $searchModel common\models\search\PaymeTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payme Transactions';
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
<?= \soft\grid\GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            /** @see soft\widget\button\Button for other configurations */
            'modal' => true,
        ]
    ],
    'columns' => [
        'id',
        'paycom_transaction_id',
        'paycom_time',
        'paycom_time_datetime:datetime',
        'create_time:datetime',
        'perform_time:datetime',
        //'cancel_time:datetime',
        'amount',
        'state',
        //'reason',
        //'receivers',
        'order_id',
        //'time:datetime',
        'actionColumn' => [
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
    