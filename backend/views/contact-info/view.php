<?php


/* @var $this soft\web\View */
/* @var $model common\models\ContactInfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contact Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
//        'id',
        [
            'attribute' => 'imageUrl',
            'label' => "Rasm",
            'format' => ['image', ['width' => '40px']]
        ],
        'phone',
        'support_phone',
        'support_description',
        'support_description_uz',
        'support_description_ru',
//        'support_description_en',
        'statusBadge:raw',
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
