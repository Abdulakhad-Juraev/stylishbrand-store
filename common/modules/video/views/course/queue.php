<?php

use soft\grid\GridView;
use soft\grid\ViewLinkColumn;

/* @var $this \soft\web\View */
/* @var $searchModel \common\modules\film\models\search\FilmSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'Navbatda turgan videolar';
$this->addBreadCrumb('Filmlar', 'index');
$this->addBreadCrumb($this->title);
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
        [
            'class' => ViewLinkColumn::class,
            'attribute' => 'fullName'
        ],
        'streamStatusName',
        'streamPercent',
        'stream_status_comment',
        [
            'class' => \soft\grid\ActionColumn::class,
            'template' => '{add-to-queue}',
            'buttons' => [
                'add-to-queue' => function ($url, $model, $key) {
                    /** @var \common\modules\film\models\Film $model */
                    return $model->addToQueueButton(true);
                }
            ]
        ]

    ],
]); ?>
