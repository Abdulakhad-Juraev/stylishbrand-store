<?php

use soft\helpers\SiteHelper;
use soft\helpers\Url;
use soft\helpers\Html;
use soft\web\View;

/* @var $this View */
/* @var $content string */


$title = $this->metaTitle ?? SiteHelper::siteTitle();
$description = $this->metaDescription ?? SiteHelper::siteDescription();
$keywords = $this->metaKeywords ?? SiteHelper::siteKeywords();
$image = $this->metaImage ?? SiteHelper::siteLogo();
$imageUrl = '';
if (!empty($image)) {
    $imageUrl = Yii::$app->urlManager->createAbsoluteUrl($image);
}

$url = Yii::$app->urlManager->createAbsoluteUrl(Url::current());

//Google Meta Tags

$this->registerMetaTag(['name' => 'title', 'content' => $title], 'title');
$this->registerMetaTag(['name' => 'description', 'content' => $description], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords], 'keywords');

// Open Graph
$this->registerMetaTag(['property' => 'og:type', 'content' => "website"], 'ogType');
$this->registerMetaTag(['property' => 'og:url', 'content' => $url], 'ogUrl');
$this->registerMetaTag(['property' => 'og:title', 'content' => $title], 'ogTitle');
$this->registerMetaTag(['property' => 'og:description', 'content' => $description], 'ogDescription');
$this->registerMetaTag(['property' => 'og:image', 'content' => $imageUrl], 'ogImage');

// Twitter
$this->registerMetaTag(['property' => "twitter:card", 'content' => "summary_large_image"], "twitterCard");
$this->registerMetaTag(['property' => "twitter:url", 'content' => $url], 'twitterUrl');
$this->registerMetaTag(['property' => "twitter:title", 'content' => $title], 'twitterTitle');
$this->registerMetaTag(['property' => "twitter:description", 'content' => $description], 'twitterDescription');
$this->registerMetaTag(['property' => "twitter:image", 'content' => $imageUrl], 'twitterImage');

$isHomePage = Url::isHomePage();
$lang = Yii::$app->language;

?>

<?php $this->beginPage() ?>

    <!DOCTYPE html>

    <html lang="<?= Yii::$app->language ?>" class="h-100">

    <head>

        <meta charset="<?= Yii::$app->charset ?>">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php $this->registerCsrfMetaTags() ?>

        <title><?= Html::encode($this->title) ?></title>

        <?= SiteHelper::favicon() ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.min.css" integrity="sha512-cyIcYOviYhF0bHIhzXWJQ/7xnaBuIIOecYoPZBgJHQKFPo+TOBA+BY1EnTpmM8yKDU4ZdI3UGccNGCEUdfbBqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <?php $this->head() ?>

    </head>

    <body>

    <div class="hidden-menu">

    </div>


    <?php $this->beginBody() ?>


    <?= $content ?>

    <?php $this->endBody() ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.all.min.js" integrity="sha512-IZ95TbsPTDl3eT5GwqTJH/14xZ2feLEGJRbII6bRKtE/HC6x3N4cHye7yyikadgAsuiddCY2+6gMntpVHL1gHw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </body>
    </html>

<?php $this->endPage();
