<?php

use common\modules\video\models\Video;
use soft\helpers\Html;
use yii\bootstrap4\Tabs;
use soft\widget\adminlte3\Card;


/* @var $this soft\web\View */
/* @var $model Video */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Kurslar', 'url' => ['index']];

if ($model->getIsPartial()) {
    $parent = $model->parent;
    $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['view', 'id' => $parent->id]];
    if ($model->season) {
        $this->params['breadcrumbs'][] = ['label' => "Modul darslari", 'url' => ['course/parts', 'id' => $parent->id]];
        $this->params['breadcrumbs'][] = ['label' => $model->season->name, 'url' => ['course/seasons', 'id' => $parent->id]];
    }
}


$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>

<?php Card::begin() ?>

<?= Tabs::widget([
    'itemOptions' => ['class' => 'pt-3'],
    'encodeLabels' => false,
    'items' => [
        [
            'label' => Html::withIcon("Umumiy ma'lumotlar", 'info-circle'),
            'content' => $this->render('_view-general', ['model' => $model]),
            'active' => true,
        ],
        [
            'label' => Html::withIcon("Video", 'video'),
            'content' => $this->render('_view-video', ['model' => $model]),
            'visible' => !$model->getIsSerial() && $model->isVideo,

        ],
        [
            'label' => Html::withIcon("Potkast", 'music'),
            'content' => $this->render('_view-audio', ['model' => $model]),
            'visible' => !$model->getIsSerial() && $model->isAudio,

        ],
    ]

]) ?>

<?php Card::end() ?>
