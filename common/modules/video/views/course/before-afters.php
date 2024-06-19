<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 01-Apr-24, 11:30
 */

use common\modules\video\models\BeforeAfterCourse;
use common\modules\video\models\Video;
use soft\grid\GridView;
use soft\grid\StatusColumn;

/* @var $this soft\web\View */
/* @var $model Video */
/* @var $searchModel common\modules\video\models\search\BeforeAfterCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kursni tamomlagandan keyin va oldin. ' . $model->name;
$this->addBreadCrumb('Kurslar', ['course/index']);
$this->addBreadCrumb($model->name, ['course/view', 'id' => $model->id]);
$this->addBreadCrumb('Kursni tamomlagandan keyin va oldin');
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
            /** @see soft\widget\button\Button for other configurations */
            'modal' => true,
            'url' => ['before-after-course/create', 'video_id' => $model->id],
        ]
    ],
    'columns' => [
//        'id',
        'name_uz',
        [
            'attribute' => 'before_after_type_id',
            'format' => 'raw',
            'filter' => BeforeAfterCourse::types(),
            'value' => function (BeforeAfterCourse $model) {
                return $model->getTypeName();
            }
        ],
//        'name_ru',
//        'name_en',
        [
            'class' => StatusColumn::class,
        ],
//        'created_by',
//        'updated_by',
        'created_at',
        //'updated_at',
        'actionColumn' => [
            'controller' => 'before-after-course',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
