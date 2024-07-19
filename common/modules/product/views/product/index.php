<?php

use common\modules\product\models\Country;
use common\modules\product\models\Product;
use common\modules\product\models\search\ProductSearch;
use common\modules\product\models\SubCategory;
use soft\grid\GridView;
use soft\grid\StatusColumn;
use soft\grid\ViewLinkColumn;
use soft\helpers\Html;

/* @var $this soft\web\View */
/* @var $searchModel ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            'modal' => false,
        ]
    ],
    'columns' => [
        [
            'class' => ViewLinkColumn::class,
            'attribute' => 'name',
        ],
        'price:sum',
        'percentage',
//        [
//            'attribute' => 'sub_category_id',
//            'filter' => SubCategory::map(),
//            'value' => function (Product $model) {
//                return $model->subCategory->name ?? '';
//            }
//        ],
        [
            'attribute' => 'country_id',
            'filter' => Country::map(),
            'value' => function (Product $model) {
                return $model->country->name ?? '';
            }
        ],
        'published_at:datetime',
        'expired_at:datetime',
//        'is_stock',
        [
            'class' => StatusColumn::class
        ],

        'actionColumn',
    ],
]); ?>
    