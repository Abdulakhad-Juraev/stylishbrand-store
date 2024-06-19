<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 13.07.2021, 15:18
 */

/* @var $this soft\web\View */
/* @var $district \backend\modules\regionmanager\models\District */
/* @var $searchModel backend\modules\regionmanager\models\search\QuarterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $district->name . ' - Hududlar';
$this->addBreadCrumb('Viloyatlar', ['region/index']);
$this->addBreadCrumb($district->region->name, ['district/index', 'region_id' => $district->region_id]);
$this->params['breadcrumbs'][] = $district->name;
$this->params['breadcrumbs'][] = 'Hududlar';
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
            'url' => ['create', 'district_id' => $district->id],
        ]
    ],
    'cols' => [
        'name',
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
    