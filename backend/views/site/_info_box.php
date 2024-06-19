<?php

use api\modules\profile\models\UserPayment;
use common\models\User;
use common\modules\book\models\Book;
use common\modules\podcast\models\Podcast;
use common\modules\userBalance\models\UserTariff;
use common\modules\video\models\Video;
use soft\helpers\Url;
use soft\web\View;
use soft\widget\adminlte3\InfoBoxWidget;


/** @var View $this */

$favoriteFilmCount = 0;
$totalPaymentSum = UserPayment::find()->sum('amount');
$totalPaymentSum = Yii::$app->formatter->asInteger($totalPaymentSum);

$usersCount = User::find()
    ->andWhere(['!=', 'id', Yii::$app->user->getId()])->count();

$videoCount = Video::find()
    ->andWhere(['serial_type_id' => Video::SERIAL_TYPE_SINGLE])
    ->count();

$courseCount = Video::find()
    ->andWhere(['serial_type_id' => Video::SERIAL_TYPE_SERIAL])
    ->count();

$bookCount = Book::find()
    ->count();

$podcastCount = Podcast::find()
    ->count();

$activeTariffUserCount = UserTariff::find()
    ->select('user_id')
    ->nonExpired()
    ->groupBy('user_id')
    ->count();

$formatter = Yii::$app->formatter;
$admin = Yii::$app->user->can('admin');
?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if ($admin): ?>
                    <div class="col-12 col-sm-6 col-md-3">
                        <a href="<?= Url::to(['/user-manager/user-payment/index']) ?>">
                            <?= InfoBoxWidget::widget(
                                [
                                    'iconBackground' => InfoBoxWidget::TYPE_INFO,
                                    'number' => $totalPaymentSum . " so'm",
                                    'text' => 'Jami to\'lovlar',
                                    'icon' => 'fas fa-coins',
                                ]
                            ); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($admin): ?>
                    <div class="col-12 col-sm-6 col-md-3">
                        <a href="<?= Url::to(['/user-manager/user/index']) ?>">
                            <?= InfoBoxWidget::widget(
                                [
                                    'iconBackground' => InfoBoxWidget::TYPE_SUCCESS,
                                    'number' => $formatter->asInteger($usersCount),
                                    'text' => 'Jami foydalanuvchilar',
                                    'icon' => 'fas fa-users',
                                ]
                            ); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="<?= Url::to(['/video-manager/video']) ?>">
                        <?= InfoBoxWidget::widget(
                            [
                                'iconBackground' => InfoBoxWidget::TYPE_WARNING,
                                'number' => $formatter->asInteger($videoCount),
                                'text' => 'Videolar soni',
                                'icon' => 'fas fa-film',
                            ]
                        ); ?>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="<?= Url::to(['/book-manager/book/index']) ?>">
                        <?= InfoBoxWidget::widget(
                            [
                                'iconBackground' => InfoBoxWidget::TYPE_INFO,
                                'number' => $formatter->asInteger($bookCount),
                                'text' => 'Kitoblar soni',
                                'icon' => 'fas fa-book',
                            ]
                        ); ?>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="<?= Url::to(['/video-manager/course/index']) ?>">
                        <?= InfoBoxWidget::widget(
                            [
                                'iconBackground' => InfoBoxWidget::TYPE_PRIMARY,
                                'number' => $formatter->asInteger($courseCount),
                                'text' => 'Kurslar soni',
                                'icon' => 'fas fa-film',
                            ]
                        ); ?>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="<?= Url::to(['/user-manager/user/index']) ?>">
                        <?= InfoBoxWidget::widget(
                            [
                                'iconBackground' => InfoBoxWidget::TYPE_SUCCESS,
                                'number' => $formatter->asInteger($activeTariffUserCount),
                                'text' => 'Aktiv obunasi bor foy.',
                                'icon' => 'fas fa-users',
                            ]
                        ); ?>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <a href="<?= Url::to(['/podcast-manager/podcast/index']) ?>">
                        <?= InfoBoxWidget::widget(
                            [
                                'iconBackground' => InfoBoxWidget::TYPE_DEFAULT,
                                'number' => $formatter->asInteger($podcastCount),
                                'text' => 'Barcha podkastlar',
                                'icon' => 'fas fa-microphone-alt',
                            ]
                        ); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <hr>
<?= $this->render('_info_box_enroll') ?>