<?php

use common\modules\film\columns\FilmStreamSrcColumn;
use common\modules\video\columns\VideoStreamSrcColumn;
use common\modules\video\models\Video;
use common\modules\video\models\VideoCategory;
use soft\grid\ActionColumn;
use soft\grid\GridView;
use soft\grid\StatusColumn;
use soft\grid\ViewLinkColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\video\models\search\VideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Videolar';
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
            'modal' => false,
        ]
    ],
    'columns' => [

        'previewImageUrl:thumbnail:Rasm',
        [
            'class' => ViewLinkColumn::class,
            'attribute' => 'filmName',
            'value' => 'name',
            'label' => 'Video nomi'
        ],
        [
            'attribute' => 'price_type_id',
            'value' => 'priceTypeName',
            'filter' => Video::priceTypes(),
        ],
        [
            'attribute' => 'serial_type_id',
            'value' => 'serialTypeName',
            'filter' => Video::serialTypes(),
        ],
        [
            'attribute' => 'category_id',
            'value' => 'category.name',
            'filter' => VideoCategory::map(),
        ],
        [
            'class' => StatusColumn::class,
        ],
        'sort_order',
//        'countInfo:raw',
        'published_at:datetime',
        [
            'class' => VideoStreamSrcColumn::class,
        ],
        [
            'class' => ActionColumn::class,
        ],
    ],
]); ?>
    