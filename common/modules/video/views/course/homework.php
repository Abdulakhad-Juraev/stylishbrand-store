<?php

use common\modules\video\models\Homework;
use common\modules\video\models\search\HomeworkSearch;
use soft\grid\StatusColumn;
use soft\helpers\Html;
use soft\web\View;
use yii\data\ActiveDataProvider;
use soft\grid\GridView;

/* @var $this View */
/* @var $searchModel HomeworkSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $model Homework */

$this->registerAjaxCrudAssets();

$this->title = 'Uy Vazifalar. ';


//$this->addBreadCrumb('Kurslar');
$this->addBreadCrumb('Kurslar', ['/video-manager/course/index']);
//$this->addBreadCrumb();



if ($model->getIsPartial()) {
    $parent = $model->parent;
    $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['view', 'id' => $parent->id]];
    if ($model->season) {
        $this->params['breadcrumbs'][] = ['label' => "Modul darslari", 'url' => ['course/parts', 'id' => $parent->id]];
        $this->params['breadcrumbs'][] = ['label' => $model->season->name,'url' => ['course/seasons', 'id' => $parent->id]];
    }
}
$this->addBreadCrumb($model->name, ['/video-manager/course/view', 'id' => $model->id]);
$this->addBreadCrumb('Vazifalar');

//$this->addBreadCrumb($model->fullName, ['/video-manager/course/view', 'id' => $model->id]);
//$this->addBreadCrumb('Fikrlar');
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
            'url' => ['homework/create', 'video_id' => $model->id],
        ]
    ],
    'columns' => [
        'title',
        [
            'attribute' => 'file_url',
            'format' => 'raw',
            'value' => function (Homework $model) {
                return $model->file_url ? Html::a("<i class='fas fa-arrow-down'></i> Yuklab olish", $model->getFileUrl(), ['download' => true, 'data-pjax' => '0']) : 'Yuklanmagan';
            },
        ],
        [
            'class' => StatusColumn::class,
        ],
        'actionColumn' => [
            'controller' => 'homework',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
