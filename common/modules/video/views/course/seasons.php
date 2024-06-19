<?php


use common\modules\video\models\search\VideoSeasonSearch;
use common\modules\video\models\Video;
use common\modules\video\models\VideoSeason;
use soft\grid\ActionColumn;
use soft\grid\GridView;
use soft\grid\StatusColumn;
use soft\helpers\Html;
use soft\web\View;
use yii\data\ActiveDataProvider;

/* @var $this View */
/* @var $model Video */
/* @var $searchModel VideoSeasonSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Modullar. ' . $model->name;
$this->addBreadCrumb('Kurslar', ['course/index']);
$this->addBreadCrumb($model->name, ['course/view', 'id' => $model->id]);
$this->addBreadCrumb('Modullar');
$this->registerAjaxCrudAssets();

?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>

<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
//    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            /** @see soft\widget\button\Button for other configurations */
            'modal' => true,
            'url' => ['video-season/create', 'video_id' => $model->id],
        ]
    ],
    'columns' => [
        'name_uz',
        'name_ru',
//        'name_en',
        'slug',
        'partsCount',
        'activePartsCount',
        [
            'attribute' => 'myVideoCount',
            'format' => 'raw',
            'value' => function (VideoSeason $videoSeason) {
                return $videoSeason->getActiveVideoCount() . ' / ' . $videoSeason->getVideoCount();
            },
        ],
        [
            'attribute' => 'myPodcastCount',
            'format' => 'raw',
            'value' => function (VideoSeason $videoSeason) {
                return $videoSeason->getActivePodcastCount() . ' / ' . $videoSeason->getPodcastCount();
            },
        ],
        [
            'class' => StatusColumn::class,
        ],
        'actionColumn' => [

            'class' => ActionColumn::class,
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-eye"></i>', $url, [
                        'title' => "Ko'rish", 'role' => 'modal-remote',
                    ]);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                        'title' => "O'zgartirish", 'role' => 'modal-remote',
                    ]);
                },
            ],

//            'viewOptions' => [
//                'role' => 'modal-remote',
//            ],
//            'updateOptions' => [
//                'role' => 'modal-remote',
//            ],
            'controller' => 'video-season',
        ],
    ],
]); ?>

