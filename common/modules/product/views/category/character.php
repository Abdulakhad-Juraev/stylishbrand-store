<?php


use soft\grid\GridView;
use soft\grid\StatusColumn;
use common\modules\product\models\Category;
use common\modules\product\models\search\CategoryCharacterSearch;


/* @var $model Category */
/* @var $this soft\web\View */
/* @var $searchModel CategoryCharacterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kategoriyalar ' . $model->name;
$this->addBreadCrumb('Kategoriyalar', ['category/index']);
$this->addBreadCrumb($model->name, ['category/view', 'id' => $model->id]);
$this->addBreadCrumb('Quyi kategoriyalar');
$this->registerAjaxCrudAssets();


?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>

<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            'modal' => true,
            'url' => ['category-character/create', 'category_id' => $model->id],
        ]
    ],
    'columns' => [
        'name',
        [
            'class' => StatusColumn::class,
        ],
        'actionColumn' => [
            'controller' => 'category-character',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>