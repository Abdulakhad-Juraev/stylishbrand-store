<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 19-Apr-24, 15:54
 */

use common\modules\video\models\Video;
use soft\web\View;
use soft\widget\bs4\Card;
use soft\widget\bs4\DetailView;
use soft\widget\button\ConfirmButton;

/* @var $this View */
/* @var $model Video */
?>

<div class="row">
    <div class="col-md-8">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
//        'id',
//                'name_uz',
//                'name_ru',
//                'name_en',
//                'category.name',
//                'slug',
//                [
//                    'attribute' => 'imageUrl',
//                    'label' => "Rasm",
//                    'format' => ['image', ['width' => '40px']]
//                ],
//                'priceTypeLabel:raw',
                'audio_org_src',
                'formatSizeUnits',
//                'published_at:datetime',
//                'audio_statusBadge:raw',
                'audio_has_org_src:bool',
                'audio_duration',
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
    </div>
    <div class="col-md-4">
        <?php Card::begin() ?>
        <p>

            <a href="<?= to(['upload-audio', 'id' => $model->id]) ?>" class="btn btn-success">
                <i class="fas fa-upload"></i> Audio yuklash
            </a>

            <?php if ($model->audio_has_org_src): ?>
                <?= ConfirmButton::widget([
                    'ajax' => false,
                    'url' => ['delete-audio', 'id' => $model->id],
                    'content' => "Audioni o'chirish",
                    'cssClass' => 'btn btn-danger',
                    'icon' => 'trash-alt',
                    'confirmMessage' => "Siz rostdan ham ushbu darsga yuklangan audioni o'chirishni xoxlaysimi?",
                ]) ?>

            <?php endif; ?>

            <?php if ($model->audio_has_org_src): ?>
                <audio controls style="margin-top: 20px">
                    <source src="<?= $model->getAudioSource() ?>" type="audio/ogg">
                    Your browser does not support the audio tag.
                </audio>
            <?php endif; ?>

        </p>
        <?php Card::end() ?>
    </div>
</div>
