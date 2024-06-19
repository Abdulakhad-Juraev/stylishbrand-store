<?php

use soft\widget\adminlte3\InfoBoxWidget;

$this->title = Yii::$app->name;

$user = Yii::$app->user;

?>

<?='' //$this->render('_info_box') ?>

<div class="row">
    <div class="col-md-12">
        <?= $this->render('_daily_registrants') ?>
    </div>
</div>
