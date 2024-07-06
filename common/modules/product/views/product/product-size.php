<?php


use common\modules\product\models\AssignProductSize;
use common\modules\product\models\Product;
use common\modules\product\models\search\AssignProductSizeSearch;
use soft\grid\GridView;
use soft\grid\StatusColumn;


/* @var $model Product */
/* @var $this soft\web\View */
/* @var $searchModel AssignProductSizeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Size ' . $model->name;
$this->addBreadCrumb('Category', ['category/index']);
$this->addBreadCrumb($model->name, ['category/view', 'id' => $model->id]);
$this->addBreadCrumb('Sub Category');
$this->registerAjaxCrudAssets();


?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>

<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            'modal' => true,
            'url' => ['assign-product-size/create', 'product_id' => $model->id],
        ]
    ],
    'columns' => [
        [
            'attribute' => 'size',
            'value' => function (AssignProductSize $model) {
                return $model->sizes->name ?? '';
            }
        ],

        [
            'class' => StatusColumn::class
        ],

        'actionColumn' => [
    'controller' => 'assign-product-size',
    'viewOptions' => [
        'role' => 'modal-remote',
    ],
    'updateOptions' => [
        'role' => 'modal-remote',
    ],
],
    ],
]); ?>