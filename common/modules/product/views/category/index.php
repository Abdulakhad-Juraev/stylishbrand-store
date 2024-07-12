<?php

use soft\grid\GridView;
use soft\grid\StatusColumn;
use soft\grid\ViewLinkColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\product\models\search\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
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
            'label' => t('Image'),
            'format' => ['image', ['width' => '40px']]
        ],
        [
            'class' => ViewLinkColumn::class,
            'attribute' => 'name',
        ],
        ['class' => StatusColumn::class],
        'actionColumn',
    ],
]); ?>
    