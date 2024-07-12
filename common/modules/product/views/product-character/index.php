<?php

use common\modules\product\models\CategoryCharacter;
use common\modules\product\models\ProductCharacter;
use soft\grid\GridView;
use soft\grid\StatusColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\product\models\search\ProductCharacterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Product Characters');
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
            /** @see soft\widget\button\Button for other configurations */
            'modal' => true,
        ]
    ],
    'columns' => [
        'title',
        [
            'attribute' => 'category_character_id',
            'filter' => CategoryCharacter::map(),
            'value' => function ($model) {
                return $model->categoryCharacter->name ?? '';
            }
        ],
        [
            'attribute' => 'product_id',
            'value' => function ($model) {
                return $model->product->name ?? '';
            }
        ],
        ['class' => StatusColumn::class],
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
    