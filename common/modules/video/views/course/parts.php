<?php


use common\modules\video\columns\VideoStreamSrcColumn;
use common\modules\video\models\search\VideoSearch;
use common\modules\video\models\Video;
use soft\grid\ActionColumn;
use soft\grid\GridView;
use soft\grid\StatusColumn;
use soft\grid\ViewLinkColumn;
use soft\helpers\Html;

/* @var $this soft\web\View */
/* @var $searchModel VideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Video */

$this->title = 'Qismlar.' . $model->name;
$this->addBreadCrumb("Kurslar", ['course/index']);
$this->addBreadCrumb($model->name, ['course/view', 'id' => $model->id]);
$this->params['breadcrumbs'][] = 'Darslar';
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
            /** @see soft\widget\button\Button for other configurations */
            'modal' => false,
            'url' => ['create-part', 'id' => $model->id],
        ]
    ],
    'columns' => [
        [
            'class' => ViewLinkColumn::class,
            'attribute' => 'filmName',
            'value' => 'name',
            'label' => 'Dars nomi'
        ],
        [
            'attribute' => 'season_id',
            'value' => 'season.name',
            'filter' => $model->seasonsMap(),
        ],
        [
            'class' => StatusColumn::class,
        ],
        'countInfo:raw',
        [
            'class' => VideoStreamSrcColumn::class,
        ],
        'sort_order',
        'published_at:datetime',
        [
            'attribute' => 'badWeekDayAssign',
            'format' => 'raw',
            'value' => function (Video $model) {
                return $model->getBadWeekDayAssign();
            }
        ],
        [
            'attribute' => 'media_type_id',
            'value' => function (Video $model) {
                return $model->getMediaTypeName();
            },
            'filter' => Video::mediaTypes(),
        ],
        'created_at:datetime',
        'actionColumn' => [

            'class' => ActionColumn::class,
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-eye"></i>', $url, [
                        'title' => "Ko'rish", 'data-pjax' => '0',
                    ]);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                        'title' => "O'zgartirish", 'data-pjax' => '0',
                    ]);
                },
            ],
        ]
    ],
]); ?>
