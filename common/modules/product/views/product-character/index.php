<?php

use common\modules\product\models\ProductCharacter;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\product\models\search\ProductCharacterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Product Characters');
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
            'title',
            'category_character_id',
            'status',
            'created_by',
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
    