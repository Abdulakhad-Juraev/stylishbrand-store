<?php

use common\models\Social;
use soft\grid\StatusColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\models\search\SocialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ijtimoiy tarmoq';
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
            [
                'attribute' => 'imageUrl',
                'label' => "Rasm",
                'format' => ['image', ['width' => '40px']]
            ],
            'url:url',
            [ 'class' => StatusColumn::class, ],
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
    