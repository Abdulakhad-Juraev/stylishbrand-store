<?php

/** @var string $thumbnail */
/** @var string $field */


?>

<div class="fileinput fileinput-new <?= !empty($thumbnail) ? 'thumbnail-exists' : '' ?>" data-provides="fileinput">
    <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
        <?= $thumbnail; ?>
    </div>
    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>

    <div>
        <span class="btn-file">
            <span class="fileinput-new btn btn-primary">

                <?= \Yii::t('file-input', 'Select image') ?>
            </span>
            <span class="fileinput-exists btn btn-info">
                 <i class="fa fa-edit"></i>
                <?= \Yii::t('file-input', 'Change') ?>
            </span>
            <?= $field; ?>
        </span>
        <span>
                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">
                      <i class="fa fa-trash-alt"></i>
                    <?= \Yii::t('file-input', 'Remove') ?>
                </a>
        </span>
    </div>
</div>
