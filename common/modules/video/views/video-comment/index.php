<?php

use common\modules\video\models\VideoComment;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\video\models\search\VideoCommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Video Comments';
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
            'user_id',
            'video_id',
            'comment:ntext',
            'status',
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
    