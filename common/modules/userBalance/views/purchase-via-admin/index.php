<?php

use common\modules\userBalance\models\PurchaseViaAdmin;
use soft\grid\StatusColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\userBalance\models\search\PurchaseViaAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Adminlar orqali xarid qilish';
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
        'full_name',
        'phone',
        [
            'attribute' => 'type_id',
            'format' => 'raw',
            'filter' => PurchaseViaAdmin::tariffCourseBooktypes(),
            'value' => function (PurchaseViaAdmin $model) {
                return $model->tariffCourseBooktypes() ? $model->getTariffCourseBookTypeName() : '';
            }
        ],
        'status:bool',
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
    