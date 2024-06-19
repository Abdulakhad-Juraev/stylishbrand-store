<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 01-Apr-24, 12:35
 */


use common\modules\video\models\Video;
use soft\grid\GridView;
use soft\grid\StatusColumn;

/* @var $this soft\web\View */
/* @var $model Video */
/* @var $searchModel common\modules\video\models\search\VideoAdditionalOptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Qo'shimcha sifatlar" . $model->name;
$this->addBreadCrumb('Kurslar', ['course/index']);
$this->addBreadCrumb($model->name, ['course/view', 'id' => $model->id]);
$this->addBreadCrumb("Qo'shimcha sifatlar");
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
            'url' => ['video-additional-option/create', 'video_id' => $model->id],
        ]
    ],
    'columns' => [
//        'id',
        [
            'attribute' => 'imageUrl',
            'label' => "Rasm",
            'format' => ['image', ['width' => '40px']]
        ],
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
            'controller' => 'video-additional-option',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>


