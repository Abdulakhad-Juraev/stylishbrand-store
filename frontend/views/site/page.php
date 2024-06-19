<?php

use frontend\models\Menu;
use frontend\models\Template;
use frontend\widgets\RightMenuWidget;
use soft\helpers\SiteHelper;

/* @var $this \soft\web\View */
/* @var $model \common\modules\page\models\Page */

$this->title = $model->title;
$this->addBreadCrumb($this->title);
$this->metaTitle = $this->title;
$this->metaDescription = $model->getShortContent();

?>

<div class="main-content">
    <div class="container">
        <div class="content">
            <div class="top">
                <?= Template::renderSocialShareView() ?>
                <?= Template::renderPrintButton('print-area') ?>
            </div>
            <div class="text" id="print-area" style="margin:0 20px">
                <?= as_html($model->content) ?>
            </div>

        </div>

        <div class="links">
            <div class="site-links">
                <?= RightMenuWidget::widget([
                    'type' => Menu::TYPE_PAGE,
                    'value' => $model->slug
                ]) ?>
            </div>

            <?= Template::renderRightPanelImage() ?>

        </div>

    </div>
</div>
