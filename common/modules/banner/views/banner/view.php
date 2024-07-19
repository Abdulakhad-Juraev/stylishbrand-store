<?php


/* @var $this soft\web\View */

/* @var $model common\modules\banner\models\Banner */

use common\modules\banner\models\Banner;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Banerlar'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'title_uz',
        'title_ru',
        'description_uz',
        'description_ru',
        [
            'attribute' => 'imageUrl',
            'label' => "Rasm",
            'format' => ['image', ['width' => '40px']]
        ],
        'count',
        'button_url:url',
        [
            'attribute' => 'type',
            'value' => function (Banner $model) {
                return $model->getTypeName() ?? '';
            }
        ],
        'statusBadge:raw',
    ],
]) ?>
