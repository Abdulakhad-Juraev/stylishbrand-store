<?php


/* @var $this soft\web\View */
/* @var $model common\modules\banner\models\Menu */

use soft\widget\kartik\file\SingleImageFileInput;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menyu'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= \soft\widget\bs4\DetailView::widget([
        'model' => $model,
        'attributes' => [
              'id',
              'phone',
            [
                'attribute' => 'imageUrl',
                'label' => "Rasm",
                'format' => ['image', ['width' => '40px']]
            ],
                      ],
    ]) ?>
