<?php


/* @var $this soft\web\View */

/* @var $model common\modules\video\models\BeforeAfterCourse */

use common\modules\video\models\BeforeAfterCourse;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Before After Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name_uz',
        'name_ru',
//        'name_en',
//        'video_id',
        'statusBadge:raw',
        [
            'attribute' => 'before_after_type_id',
            'format' => 'raw',
            'value' => function (BeforeAfterCourse $model) {
                return $model->getTypeName();
            }
        ],
//        'created_by',
//        'updated_by',
//        'created_at',
//        'updated_at',
        'created_at',
        'createdBy.fullname',
        'updated_at',
        'updatedBy.fullname'
    ],
]) ?>
