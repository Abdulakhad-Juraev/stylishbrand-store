<?php

use common\modules\video\models\Video;
use soft\web\View;
use soft\widget\bs4\DetailView;
use soft\widget\button\ConfirmButton;

/* @var $this View */
/* @var $model Video */


?>


<div class="row">

    <div class="col-md-6">
        <p>

            <a href="<?= to(['upload-video', 'id' => $model->id]) ?>" class="btn btn-success">
                <i class="fas fa-upload"></i> Video yuklash
            </a>

            <?php if ($model->has_streamed_src || $model->has_org_src): ?>
                <?= ConfirmButton::widget([
                    'ajax' => false,
                    'url' => ['delete-video', 'id' => $model->id],
                    'content' => "Videoni o'chirish",
                    'cssClass' => 'btn btn-danger',
                    'icon' => 'trash-alt',
                    'confirmMessage' => "Siz rostdan ham ushbu filmga yuklangan videoni o'chirishni xoxlaysimi?",
                ]) ?>
            <?php endif; ?>

            <?= $model->addToQueueButton() ?>

        </p>

        <?= $model->getVideoPlayerField() ?>
    </div>
    <div class="col-md-6">
        <?= DetailView::widget([
            'model' => $model,
            'toolbar' => false,
            'initPanel' => false,
            'before' => '',
            'attributes' => [
                'media_duration:gmtime',
                'media_size:fileSize',
                'has_org_src:bool',
                [
                    'attribute' => 'org_src',
                    'visible' => $model->has_org_src,
                ],
                'has_streamed_src:bool',
                [
                    'attribute' => 'stream_src',
                    'visible' => $model->has_streamed_src,
                ],
                [
                    'attribute' => 'representations',
                    'visible' => $model->has_streamed_src,
                ],
                'streamStatusName',
                'streamPercent',
                'stream_status_comment'
            ],
        ]) ?>
    </div>

</div>
