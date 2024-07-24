<?php


use common\modules\product\models\AssignProductSize;
use common\modules\product\models\Product;
use common\modules\product\models\search\AssignProductSizeSearch;
use common\modules\product\models\search\ProductImageSearch;
use soft\grid\GridView;
use soft\grid\StatusColumn;


/* @var $model Product */
/* @var $this soft\web\View */
/* @var $searchModel ProductImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Size ' . $model->name;
$this->addBreadCrumb('Mahsulotlar', ['product/index']);
$this->addBreadCrumb($model->name, ['product/view', 'id' => $model->id]);
$this->addBreadCrumb('Rasm va Rang');
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
            'url' => ['product-image/create', 'product_id' => $model->id],
        ]
    ],
    'columns' => [
        [
            'attribute' => 'imageUrl',
            'label' => "Rasm",
            'format' => ['image', ['width' => '40px']]
        ],
        [
            'attribute' => 'color_id',
            'value' => function ($model) {
                return $model->color->name ?? '';
            }
        ],

        ['class' => StatusColumn::class],
        'actionColumn' => [
            'controller' => 'product-image',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>