<?php


use common\modules\video\columns\VideoStreamSrcColumn;
use common\modules\video\models\search\VideoSearch;
use soft\grid\ActionColumn;
use soft\grid\GridView;
use soft\grid\StatusColumn;
use soft\grid\ViewLinkColumn;

/* @var $this soft\web\View */
/* @var $searchModel VideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kurslar';
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
            'label' => "Kurs nomi",
            'value' => 'name',
        ],
        'price:sum',
        'price_for_subscribers:sum',
//        'countInfo:raw',
        'published_at:datetime',
        [
            'class' => StatusColumn::class,
        ],
        'in_process:bool',
        'is_free:bool',
//        [
//            'class' => VideoStreamSrcColumn::class,
//        ],
        [
            'class' => ActionColumn::class,
        ],
    ],
]); ?>
