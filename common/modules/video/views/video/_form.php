<?php

use common\modules\video\models\Video;
use common\modules\video\models\VideoCategory;
use common\modules\video\traits\VideoPriceTypeTrait;
use soft\helpers\Html;
use soft\widget\bs4\Card;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\file\SingleImageFileInput;
use soft\widget\kartik\Form;
use soft\widget\kartik\FormGrid;

/* @var $this soft\web\View */
/* @var $model Video */

if ($model->isNewRecord || !$model->published_at) {
    $model->published_at = date('Y-m-d H:i');
}
if (is_integer($model->published_at)) {
    $model->published_at = date('Y-m-d H:i', $model->published_at);
}

if ($model->isNewRecord) {

    $model->sort_order = Video::find()
            ->andWhere(['serial_type_id' => Video::SERIAL_TYPE_SINGLE])
            ->nonPartial()
            ->count() + 1;
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
    <!--    <li class="nav-item">-->
    <!--        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"-->
    <!--           aria-controls="pills-profile" aria-selected="false">-->
    <!--            Tavsif-->
    <!--        </a>-->
    <!--    </li>-->
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
                                'name' => [
                                    'options' => [
                                        'required' => true,
                                    ]
                                ],
                            ]
                        ],
                        [
                            'columns' => 2,
                            'attributes' => [
                                'price_type_id:dropdownList' => [
                                    'items' => Video::priceTypes(),
//                                    'options' => [
//                                        'prompt' => 'Tanlang...',
//                                    ]
                                ],
                                'category_id:select2' => [
                                    'options' => [
                                        'data' => VideoCategory::map()
                                    ]
                                ],
//                                'duration_number',
//                                'duration_text:dropdownList' => [
//                                    'items' => Video::durationTexts(),
//                                ],
                            ]
                        ],
                        [
                            'columns' => 1,
                            'attributes' => [
                                'published_at:datetime',
                            ]
                        ]
                    ],

                ]); ?>
            </div>

            <div class="col-md-4">
                <code style="font-size: 11px">* Rasm o'lchami 1440 x 720px bo'lishi tavsiya etiladi!</code>
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
                        'sort_order',
                        'status:status',
                    ]
                ]); ?>
            </div>
        </div>
    </div>
    <!--    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">-->
    <!--        --><?php //= Form::widget([
    //            'model' => $model,
    //            'form' => $form,
    //            'initCard' => false,
    //            'attributes' => [
    //                'description_1:textarea' => [
    //                    'options' => [
    //                        'rows' => 6,
    //                    ]
    //                ],
    //                'description_2:textarea' => [
    //                    'options' => [
    //                        'rows' => 6,
    //                    ]
    //                ],
    //            ]
    //        ]); ?>
    <!--    </div>-->
</div>


<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>
<?php Card::end() ?>


