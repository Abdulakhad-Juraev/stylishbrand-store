<?php


use common\modules\product\models\Product;
use common\modules\product\models\search\ProductCharacterSearch;
use soft\grid\GridView;
use soft\grid\StatusColumn;
use common\modules\product\models\Category;
use common\modules\product\models\search\CategoryCharacterSearch;


/* @var $model Product */
/* @var $this soft\web\View */
/* @var $searchModel ProductCharacterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Category ' . $model->name;
$this->addBreadCrumb('Mahsulotlar', ['product/index']);
$this->addBreadCrumb($model->name, ['product/view', 'id' => $model->id]);
$this->addBreadCrumb('Qo\'shimcha malumotlar');
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
            'url' => ['product-character/create', 'product_id' => $model->id],
        ]
    ],
    'columns' => [
        'title',
        [
            'class' => StatusColumn::class,
        ],
        'actionColumn' => [
            'controller' => 'product-character',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>