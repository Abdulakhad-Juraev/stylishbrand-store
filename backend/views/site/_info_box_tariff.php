<?php

use common\modules\tariff\models\Tariff;
use common\modules\userbalance\models\UserTariff;
use soft\widget\adminlte3\InfoBoxWidget;

$tariffs = Tariff::find()
    ->all();
$formatter = Yii::$app->formatter;
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php foreach ($tariffs as $tariff): ?>
                <?php $usersCount = UserTariff::find()
                    ->andWhere(['tariff_id' => $tariff->id])
                    ->nonExpired()
                    ->count()
                ?>
                <div class="col-12 col-sm-6 col-md-3">
                    <?= InfoBoxWidget::widget(
                        [
                            'iconBackground' => InfoBoxWidget::TYPE_INFO,
                            'number' => $formatter->asInteger($usersCount),
                            'text' => "<b>" . $tariff->name . "</b>" . ' aktiv ta\'r. bor foy.',
                            'icon' => 'fas fa-users',
                        ]
                    ); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>