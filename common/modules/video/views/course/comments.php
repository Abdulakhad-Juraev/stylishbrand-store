<?php


use common\modules\video\models\Video;
use common\modules\video\models\search\VideoCommentSearch;
use soft\grid\ActionColumn;
use soft\grid\GridView;
use soft\grid\StatusColumn;
use soft\web\View;
use yii\bootstrap4\Html;
use yii\data\ActiveDataProvider;

/* @var $this View */
/* @var $searchModel VideoCommentSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $model Video */

$this->registerAjaxCrudAssets();

$this->title = 'Fikrlar. ' . $model->fullName;

$this->addBreadCrumb('Kurslar', ['/video-manager/course/index']);
$this->addBreadCrumb($model->fullName, ['/video-manager/course/view', 'id' => $model->id]);
$this->addBreadCrumb('Fikrlar');

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
            'modal' => true,
            'url' => ['/video-manager/video-comment/create', 'video_id' => $model->id],
        ]
    ],
    'columns' => [
        'user_id',
        [
            'attribute' => 'user_id',
            'value' => 'user.fullName',
        ],
        'comment',
//        'answer',
        'created_at:datetime',
        [
            'class' => StatusColumn::class,
        ],
        [
            'class' => ActionColumn::class,
            'template' => '{update} {view} {change} {delete}',
            'buttons' => [
                'change' => function ($url, $model, $key) {
                    return Html::a('<i class="fas fa-sync-alt"></i>', $url, [
                        'title' => 'O\'zgartirish', 'data-pjax' => '0']);
                },
//                'update' => function ($url, $model, $key) {
//                    return Html::a('<i class="fas fa-reply"></i>', $url, [
//                        'title' => "Javob yozish", 'role' => 'modal-remote',
//                    ]);
//                }
            ],
            'controller' => 'video-comment',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
