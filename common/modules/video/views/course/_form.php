<?php


use common\modules\video\models\Video;
use soft\helpers\Html;
use soft\widget\kartik\ActiveForm;
use soft\widget\kartik\file\SingleImageFileInput;
use soft\widget\kartik\Form;
use soft\widget\adminlte3\Card;
use soft\widget\kartik\FormGrid;

/* @var $this soft\web\View */
/* @var $model Video */

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
                                'duration_number' => [
                                    'options' => [
                                        'required' => true,
                                    ]
                                ],
                                'duration_text:dropdownList' => [
                                    'items' => Video::durationTexts(),
                                ],
                                'price' => [
                                    'options' => [
                                        'required' => true,
                                    ]
                                ],
                                'price_for_subscribers' => [
                                    'options' => [
                                        'required' => true,
                                    ]
                                ],
                            ]
                        ],
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
                        'published_at:datetime',
                        'course_days',
                        'status:status',
                        'in_process:checkbox',
                        'is_free:checkbox',
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
                'description_1:textarea' => [
                    'options' => [
                        'rows' => 6,
                    ]
                ],
                'description_2:textarea' => [
                    'options' => [
                        'rows' => 6,
                    ]
                ],
            ]
        ]); ?>
    </div>
</div>


<div class="form-group">
    <?= Html::submitButton(Yii::t('site', 'Save'), ['visible' => !$this->isAjax]) ?>
</div>

<?php ActiveForm::end(); ?>
<?php Card::end() ?>

