<?php
/**
 * @var $this View
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */

use yii\helpers\Html;
use yii\web\View;


?>
<?php echo Html::beginTag('div', $this->context->options); ?>
<!-- Gallery Toolbar -->

<div class="btn-toolbar" style="padding:4px">
    <div class="btn-group" style="display: inline-block;">
        <div class="btn btn-success btn-file" style="display: inline-block">
            <i class="fa fa-plus"></i><?php echo Yii::t('app', "Qo'shish…"); ?>
            <input type="file" name="gallery-image" class="afile" accept="image/*" multiple="multiple"/>
        </div>
    </div>
    <div class="btn-group" style="display: inline-block;">

        <label class="btn btn-default">
            <input type="checkbox" style="margin-right: 4px;" class="select_all"><?php echo Yii::t(
                'app',
                'Barchasini tanlash'
            ); ?>
        </label>
        <div class="btn btn-default disabled edit_selected">
            <i class="fa fa-pen"></i> <?php echo Yii::t('app', "O'zgartirish"); ?>
        </div>
        <div class="btn btn-default disabled remove_selected">
            <i class="fa fa-trash"></i> <?php echo Yii::t('app', "O'chirish"); ?>
        </div>
    </div>
</div>

<hr/>
<!-- Gallery Photos -->
<div class="sorter">
    <div class="images"></div>
    <br style="clear: both;"/>
</div>

<!-- Modal window to edit photo information -->
<div class="editor-modal modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><?php echo Yii::t('app', "Ma'lumotlarni o'zgartirish") ?></h3>
                <a class="close" data-dismiss="modal">×</a>

            </div>
            <div class="modal-body">
                <div class="form"></div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary save-changes">
                    <?php echo Yii::t('app', "O'zgarishlarni saqlash") ?>
                </a>
                <a href="#" class="btn" data-dismiss="modal"><?php echo Yii::t(
                        'app',
                        'Yopish'
                    ) ?></a>
            </div>
        </div>
    </div>
</div>

<div class="overlay">
    <div class="overlay-bg">&nbsp;</div>
    <div class="drop-hint">
        <span class="drop-hint-info"><?php echo Yii::t('app', 'Fayllarni tashlang…') ?></span>
    </div>
</div>
<div class="progress-overlay">
    <div class="overlay-bg">&nbsp;</div>
    <!-- Upload Progress Modal-->
    <div class="modal progress-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><?php echo Yii::t('app', 'Rasmlarni yuklash…') ?></h3>
                </div>
                <div class="modal-body">
                    <div class="progress ">
                        <div class="progress-bar progress-bar-info progress-bar-striped active upload-progress"
                             role="progressbar">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo Html::endTag('div'); ?>
