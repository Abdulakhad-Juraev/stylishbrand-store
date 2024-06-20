<?php

use common\modules\product\models\Product;
use common\modules\product\models\search\ProductSearch;
use common\modules\product\models\SubCategory;
use soft\grid\GridView;
use soft\grid\StatusColumn;
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
            'modal' => true,
        ]
    ],
    'columns' => [
        'name',
        'price:sum',
        'percentage',
        [
            'attribute' => 'sub_category_id',
            'filter' => SubCategory::map(),
            'value' => function (Product $model) {
                return $model->subCategory->name ?? '';
            }
        ],
        'published_at:datetime',
        'expired_at:datetime',
        [
            'class' => StatusColumn::class
        ],
        'actionColumn' => [
            'template' => '{image} {view} {update} {delete} ',
            'buttons' => [
                'image' => function ($url, $model, $key) {
                    $icon = Html::tag('span', '', ['class' => 'fas fa-image']);

                    return Html::a($icon, ['product/image', 'id' => $model->id], [
                        'title' => Yii::t('yii', 'Upload'),
                    ]);
                },
            ],
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
    