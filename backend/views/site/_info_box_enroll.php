<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 01-May-24, 16:24
 */

use common\modules\user\models\Enroll;
use soft\helpers\Url;
use soft\widget\adminlte3\InfoBoxWidget;

$totalEnrolls = intval(Enroll::find()->count());
$todayEnrolls = intval(Enroll::find()->today()->count());

$payedEnrolls = intval(Enroll::find()->paid()->count());
$freeEnrolls = intval(Enroll::find()->free()->count());

$todayPayedEnrolls = intval(Enroll::find()->today()->paid()->count());
$todayFreeEnrolls = intval(Enroll::find()->today()->free()->count());

$formatter = Yii::$app->formatter;
?>
<section class="content">
    <div class="container-fluid">
        <h3>Kurslar</h3>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?= Url::to(['/user-manager/enroll/index']) ?>">
                    <?= InfoBoxWidget::widget(
                        [
                            'iconBackground' => InfoBoxWidget::TYPE_INFO,
                            'number' => $formatter->asInteger($totalEnrolls),
                            'text' => "Barcha kursga a'zoliklar",
                            'icon' => 'fas fa-dollar-sign',
                        ]
                    ); ?>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?= Url::to(['/user-manager/enroll/index']) ?>">
                    <?= InfoBoxWidget::widget(
                        [
                            'iconBackground' => InfoBoxWidget::TYPE_DANGER,
                            'number' => $formatter->asInteger($freeEnrolls),
                            'text' => "Barcha bepul a'zoliklar",
                            'icon' => 'fas fa-dollar-sign',
                        ]
                    ); ?>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?= Url::to(['/user-manager/enroll/index']) ?>">
                    <?= InfoBoxWidget::widget(
                        [
                            'iconBackground' => InfoBoxWidget::TYPE_SUCCESS,
                            'number' => $formatter->asInteger($payedEnrolls),
                            'text' => "Barcha pullik a'zoliklar",
                            'icon' => 'fas fa-dollar-sign',
                        ]
                    ); ?>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?= Url::to(['/user-manager/enroll/index']) ?>">
                    <?= InfoBoxWidget::widget(
                        [
                            'iconBackground' => InfoBoxWidget::TYPE_PRIMARY,
                            'number' => $formatter->asInteger($todayEnrolls),
                            'text' => "Bugungi kursga a'zoliklar",
                            'icon' => 'fas fa-dollar-sign',
                        ]
                    ); ?>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?= Url::to(['/user-manager/enroll/index']) ?>">
                    <?= InfoBoxWidget::widget(
                        [
                            'iconBackground' => InfoBoxWidget::TYPE_DEFAULT,
                            'number' => $formatter->asInteger($todayFreeEnrolls),
                            'text' => "Bugungi bepul a'zoliklar",
                            'icon' => 'fas fa-dollar-sign',
                        ]
                    ); ?>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?= Url::to(['/user-manager/enroll/index']) ?>">
                    <?= InfoBoxWidget::widget(
                        [
                            'iconBackground' => InfoBoxWidget::TYPE_INFO,
                            'number' => $formatter->asInteger($todayPayedEnrolls),
                            'text' => "Bugungi pullik a'zoliklar",
                            'icon' => 'fas fa-dollar-sign',
                        ]
                    ); ?>
                </a>
            </div>
        </div>
</section>

