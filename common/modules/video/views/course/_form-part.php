<?php

use common\modules\video\models\Video;
use common\modules\video\models\VideoDaysWeek;
use common\modules\video\models\VideoSeason;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\file\SingleImageFileInput;
use soft\widget\kartik\Form;
use soft\widget\adminlte3\Card;
use soft\widget\kartik\FormGrid;
use soft\widget\kartik\Select2;

/* @var $this soft\web\View */
/* @var $model Video */
/* @var $parentModel Video */


if ($model->isNewRecord) {

    $model->sort_order = Video::find()
            ->andWhere(['parent_id' => $model->parent_id])
            ->count() + 1;
}

if ($model->isNewRecord || !$model->published_at) {
    $model->published_at = date('Y-m-d H:i');
}
if (is_integer($model->published_at)) {
    $model->published_at = date('Y-m-d H:i', $model->published_at);
}

?>

<?php Card::begin() ?>

<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ]
]); ?>


<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
           aria-controls="pills-home" aria-selected="true">
            Asosiy ma'lumotlar
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
           aria-controls="pills-profile" aria-selected="false">
            Tavsif
        </a>
    </li>
</ul>
<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <div class="row">

            <div class="col-md-8">
                <?= FormGrid::widget([
                    'model' => $model,
                    'form' => $form,
                    'initCard' => false,
                    'rows' => [
                        [
                            'attributes' => [
                                'name',
                            ]
                        ],
                        [
                            'columns' => 2,
                            'attributes' => [
                                'status:status',
                                'season_id:dropdownList' => [
                                    'items' => VideoSeason::map($model->parent_id),
                                ],
                                'sort_order:number',
                                'published_at:datetime',
                            ]
                        ],
                    ],

                ]); ?>
                <?= $form->field($model, 'week_days')->widget(Select2::classname(), [
                    'data' => VideoDaysWeek::weeks(),
                    'size' => Select2::MEDIUM,
                    'options' => [
                        'placeholder' => 'Hafta kunlarini tanlang ...',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>

            <div class="col-md-4">
                <code style="font-size: 11px">* Rasm o'lchami 800x1020px bo'lishi tavsiya etiladi!</code>
                <?= Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'initCard' => false,
                    'attributes' => [
                        'image:widget' => [
                            'widgetClass' => SingleImageFileInput::class,
                            'options' => [
                                'initialPreviewUrl' => $model->thumbImageUrl,
                            ]
                        ],
                        'media_type_id:dropdownList' => [
                            'items' => Video::mediaTypes(),
                        ],
                        'audio_duration'
                    ]
                ]); ?>
            </div>

        </div>
    </div>
    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
        <?= Form::widget([
            'model' => $model,
            'form' => $form,
            'initCard' => false,
            'attributes' => [
                'part_description:textarea' => [
                    'options' => [
                        'rows' => 6,
                    ]
                ],
            ]
        ]); ?>
    </div>
</div>


<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save')) ?>
</div>

<?php ActiveForm::end(); ?>
<?php Card::end() ?>
