<?php

use common\modules\banner\models\Banner;
use soft\grid\GridView;
use soft\grid\StatusColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\banner\models\search\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Banners');
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
    <?= GridView::widget([
        'id' => 'crud-datatable',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel, 
        'toolbarTemplate' => '{create}{refresh}',
        'toolbarButtons' => [
            'create' => [
                'modal' => true,
            ]
        ],
        'columns' => [
            [
                'attribute' => 'imageUrl',
                'label' => "Rasm",
                'format' => ['image', ['width' => '40px']]
            ],
            'title',
            'count',
            'button_url:url',
            ['class'=> StatusColumn::class],
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
    