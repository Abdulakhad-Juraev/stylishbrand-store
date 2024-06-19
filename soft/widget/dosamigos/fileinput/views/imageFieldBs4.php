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
        <span class="btn btn-primary btn-file btn-block">
            <span class="fileinput-new">Rasmni tanlang</span>
            <span class="fileinput-exists">O'zgartirish</span>
            <?= $field; ?>
        </span>
        <a href="#" class="btn btn-danger btn-block fileinput-exists" data-dismiss="fileinput">
            Olib tashlash
        </a>
    </div>
</div>

