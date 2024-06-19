<?php

use common\models\AppVersion;

/* @var $this soft\web\View */
/* @var $searchModel common\models\search\AppVersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ilova sozlamalari';
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
//        'id',
        'apk_version',
        'apk_url:url',
        'ios_version',
        'ios_url:url',
//        'status',
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
    