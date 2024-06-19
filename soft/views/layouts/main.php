<?php

use yii\helpers\Html;
use frontend\assets\AdminPanelAsset;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $menu array */

\frontend\assets\AdminLte3Asset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-purple sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">
    <?= $this->render('main/header.php') ?>
    <?= $this->render('main/left.php', ['menu' => $menu]) ?>
    <?= $this->render('main/content.php', ['content' => $content]) ?>
    <?= $this->render('main/footer.php') ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
