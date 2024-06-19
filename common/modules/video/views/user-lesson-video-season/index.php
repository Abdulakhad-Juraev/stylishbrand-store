<?php

use common\modules\video\models\UserLessonVideoSeason;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\video\models\search\UserLessonVideoSeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Lesson Video Seasons';
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
    <?= \soft\grid\GridView::widget([
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
                    'id',
            'parent_video_id',
            'user_id',
            'season_id',
            'copmleted_count',
            //'lesson_count',
            //'completed_percent',
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',
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
    