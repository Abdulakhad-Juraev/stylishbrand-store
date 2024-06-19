<?php

use soft\widget\bs4\DetailView;
use soft\widget\button\ConfirmButton;

/* @var $this soft\web\View */
/* @var $model common\modules\video\models\Video */


?>

<p>
    <a href="<?= to(['update', 'id' => $model->id]) ?>" class="btn btn-info"> <i class="fas fa-pencil-alt"></i>
        Tahrirlash</a>


    <?= ConfirmButton::widget([
        'ajax' => false,
        'url' => ['delete', 'id' => $model->id],
        'content' => "Videoni o'chirish",
        'cssClass' => 'btn btn-danger',
        'icon' => 'trash-alt',
    ]) ?>
</p>

<div class="row">
    <div class="col-md-7">
        <?= DetailView::widget([
            'model' => $model,
            'toolbar' => false,
            'initPanel' => false,
            'before' => '',
            'attributes' => [
                'name_uz',
                'name_ru',
//                'name_en',
                'published_at:datetime',
                'slug',
                'sort_order',
                [
                    'attribute' => 'priceTypeLabel',
                    'format' => 'raw',
                ],
//                [
//                    'attribute' => 'countInfo',
//                    'format' => 'raw',
//                ],
                'statusBadge:raw',
                'created_at',
                'createdBy.fullname',
                'updated_at',
                'updatedBy.fullname',
            ],
        ]) ?>
    </div>
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-12">
                <?= Yii::$app->formatter->asThumbnail($model->previewImageUrl, "100%") ?>
            </div>
        </div>
    </div>
</div>
