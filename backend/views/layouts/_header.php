<?php

use common\models\DollarSetting;
use soft\helpers\ArrayHelper;
use soft\helpers\Html;
use yii\helpers\Url;

$params = Yii::$app->params;
$languages = $params['languages'];
$languageParam = $params['languageParam'];
$activeLanguage = Yii::$app->request->get($languageParam, $params['defaultLanguage']);
if (!array_key_exists($activeLanguage, $params['languages'])) {
    $activeLanguage = $params['defaultLanguage'];
}
//$activeLanguage = ArrayHelper::remove($languages, $activeLanguage);

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <a href="<?= to(['/site/index']) ?>" class="nav-link"><i
                        class="fas fa-home"></i> Bosh sahifa</a>
        </li>


        <?php foreach ($languages as $key => $label): ?>

            <?php if ($activeLanguage == $key): ?>

                <li class="nav-item active">
                    <a class="nav-link"> <i class="fas fa-check"></i> <?= $label ?></a>
                </li>


            <?php else: ?>
<!--                <li class="nav-item active">-->
<!--                    <a href="--><?php //= Url::current(['lang' => $key]) ?><!--" class="nav-link text-info">--><?php //= $label ?><!--</a>-->
<!--                </li>-->
            <?php endif ?>

        <?php endforeach; ?>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <i class="fas fa-user mr-2"></i>
                <span class="hidden-xs "> <?= user()->username ?></span>
                <i class="fas fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu">
                <li class="user-header">
                    <img src="<?= Url::base() ?>/template/adminlte3//img/AdminLTELogo.png" class="img-circle"
                         alt="User Image"/>
                    <p>
                        <?= user()->username ?>
                    </p>
                </li>
                <!-- Menu Body -->
                <!-- Menu Footer-->
                <li class="user-footer">
                    <div class="float-left">
                        <a href="<?= Url::to(['/profile-manager']) ?>" class="btn btn-default btn-flat">
                            <span class="fas fa-user-cog"></span> Shaxsiy kabinet
                        </a>
                        <?= Html::a(
                            fas('sign-out-alt') . " Chiqish",
                            ['/site/logout'],
                            ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                        ) ?>
                    </div>

                </li>
            </ul>
        </li>

    </ul>
</nav>
<!-- /.navbar -->
