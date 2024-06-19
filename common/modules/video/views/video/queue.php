<?php

use common\modules\film\models\Film;
use common\modules\video\models\search\VideoSearch;
use common\modules\video\models\Video;
use soft\grid\ActionColumn;
use soft\grid\GridView;
use soft\helpers\Html;
use soft\web\View;
use yii\data\ActiveDataProvider;

/* @var $this View */
/* @var $searchModel VideoSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Navbatda turgan videolar';
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
            'attribute' => 'fullName',
            'label' => "Nomi",
            'format' => 'raw',
            'value' => function (Video $model) {
                if ($model->serial_type_id == Video::SERIAL_TYPE_SERIAL || $model->serial_type_id == Video::SERIAL_TYPE_PART) {
                    return Html::a($model->fullName, ['/video-manager/course/view', 'id' => $model->id], ['data-pjax' => '0']);
                }
                if ($model->serial_type_id == Video::SERIAL_TYPE_SINGLE) {
                    return Html::a($model->fullName, ['/video-manager/video/view', 'id' => $model->id], ['data-pjax' => '0']);
                }
            }
        ],
        'streamStatusName',
        'streamPercent',
        'stream_status_comment',
        [
            'class' => ActionColumn::class,
            'template' => '{add-to-queue}',
            'buttons' => [
                'add-to-queue' => function ($url, $model, $key) {
                    /** @var Video $model */
                    return $model->addToQueueButton(true);
                }
            ]
        ]

    ],
]); ?>
