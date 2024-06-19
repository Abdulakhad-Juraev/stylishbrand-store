<?php


use common\modules\video\models\Video;
use soft\widget\bs4\DetailView;
use soft\widget\button\ConfirmButton;
use yii\web\View;

/* @var $this View */
/* @var $model Video */

$isPartial = $model->getIsPartial();
$isSerial = $model->getIsSerial();


?>

<p>
    <a href="<?= to(['update', 'id' => $model->id]) ?>" class="btn btn-info"> <i class="fas fa-pencil-alt"></i>
        Tahrirlash</a>


    <?= ConfirmButton::widget([
        'ajax' => false,
        'url' => ['delete', 'id' => $model->id],
        'content' => "Kursni o'chirish",
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
                'course_days',
                'in_process:bool',
                'published_at:datetime',
                'slug',
                'sort_order',
                'is_free:bool',
                [
                    'attribute' => 'parent.name',
                    'visible' => $isPartial,
                ],
                [
                    'attribute' => 'season.name',
                    'visible' => $isPartial,
                ],
                [
                    'attribute' => 'priceTypeLabel',
                    'format' => 'raw',
                ],

                [
                    'attribute' => 'serialTypeName',
                    'visible' => !$isPartial,
                ],
                [
                    'attribute' => 'seasonsCount',
                    'visible' => $isSerial,
                ],
                [
                    'attribute' => 'partsCount',
                    'visible' => $isSerial,
                ],
                [
                    'attribute' => 'activePartsCount',
                    'visible' => $isSerial,
                ],


//                [
//                    'attribute' => 'countInfo',
//                    'format' => 'raw',
//                ],
                'durationName',
                [
                    'attribute' => 'price',
                    'format' => 'sum',
                    'visible' => !$isPartial,
                ],
                [
                    'attribute' => 'price_for_subscribers',
                    'format' => 'sum',
                    'visible' => !$isPartial,
                ],
                [
                    'attribute' => 'description_1_uz',
                    'visible' => !$isPartial,
                ],
                [
                    'attribute' => 'description_1_ru',
                    'visible' => !$isPartial,
                ],
//                [
//                    'attribute' => 'description_1_en',
//                    'visible' => !$isPartial,
//                ],
                [
                    'attribute' => 'description_2_uz',
                    'visible' => !$isPartial,
                ],
                [
                    'attribute' => 'description_2_ru',
                    'visible' => !$isPartial,
                ],
//                [
//                    'attribute' => 'description_2_en',
//                    'visible' => !$isPartial,
//                ],
                'statusBadge:raw',
                'created_at',
                'createdBy.fullname',
                'updated_at',
                'updatedBy.fullname',
                [
                    'attribute' => 'part_description_uz',
                    'visible' => $isPartial,
                ],
                [
                    'attribute' => 'part_description_ru',
                    'visible' => $isPartial,
                ],
                [
                    'attribute' => 'badWeekDayAssign',
                    'format' => 'raw',
                    'value' => function (Video $model) {
                        return $model->getBadWeekDayAssign();
                    },
                    'visible' => $isPartial,
                ],
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
