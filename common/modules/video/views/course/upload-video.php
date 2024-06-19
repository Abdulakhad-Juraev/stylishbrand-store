<?php


use common\modules\video\models\Video;
use soft\widget\kartik\ActiveForm;
use soft\widget\adminlte3\Card;

/* @var $this soft\web\View */
/* @var $model Video */

$this->title = 'Video yuklash';
$this->params['breadcrumbs'][] = ['label' => 'Kurslar', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Card::begin() ?>

<div id="message_area" style="display: none">
</div>


<?php if ($model->has_streamed_src || $model->has_org_src): ?>
    <div class="alert alert-warning">
        Diqqat!!! Siz rostdan ham ushbu kurs uchun yangi video yuklashni xoxlaysizmi? <br>
        Bunda avvalgi yuklangan video o'chib ketadi!!
    </div>
<?php endif ?>

<div id="upload-area">
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'org_src')->widget('\kartik\widgets\FileInput', [
        'options' => [
            'multiple' => false,
            'accept' => 'video/*',
        ],
        'language' => 'uz',
        'pluginOptions' => [
            'uploadUrl' => Yii::$app->request->absoluteUrl,
            'maxFileCount' => 1,
            'maxFileSize' => Video::maxVideoSize(),
            'dropZoneTitle' => 'Videoni yuklang',
            'msgPlaceholder' => 'Videoni tanlang',
        ],

        'pluginEvents' => [
            'fileuploaded' => "

                function(e, data) { 
    
    
                    let response = data.response
                    let message = response.message
                    let status = response.status
                    let message_area = $('#message_area')
    
                    if (status === 200){
                        
                        if (response.redirect){
                            window.location.href = response.redirect
                        }
                        else{
                            message_area.addClass('alert')
                            message_area.addClass('alert-success')
                            message_area.html(message)
                            message_area.show()
                            $('#upload-area').hide()
                            $('#success_button').show()
                        }
                        
                    }
                    else{
                        message_area.addClass('alert')
                        message_area.addClass('alert-danger')
                        message_area.html(message)
                        message_area.show()
                    }
                }
",

        ]

    ])->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>
<?php Card::end() ?>
