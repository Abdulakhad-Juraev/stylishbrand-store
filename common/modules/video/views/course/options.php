<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 30-Mar-24, 23:42
 */

use common\modules\video\models\Video;
use common\modules\video\models\VideoOption;
use soft\grid\StatusColumn;

/* @var $this soft\web\View */
/* @var $model Video */
/* @var $searchModel common\modules\video\models\search\VideoOptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sifatlari. ' . $model->name;
$this->addBreadCrumb('Kurslar', ['course/index']);
$this->addBreadCrumb($model->name, ['course/view', 'id' => $model->id]);
$this->addBreadCrumb('Sifatlari');
$this->registerAjaxCrudAssets();

?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>
<?= \soft\grid\GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            /** @see soft\widget\button\Button for other configurations */
            'modal' => true,
            'url' => ['video-option/create', 'video_id' => $model->id],
        ]
    ],
    'columns' => [
//        'id',
        'name_uz',
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
            'controller' => 'video-option',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>


