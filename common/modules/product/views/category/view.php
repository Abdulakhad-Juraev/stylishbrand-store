<?php


/* @var $this soft\web\View */

/* @var $model common\modules\product\models\Category */

use soft\widget\bs4\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_tab-menu', ['model' => $model]) ?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        [
            'attribute' => 'imageUrl',
            'label' => "Rasm",
            'format' => ['image', ['width' => '40px']]
        ],
        'statusBadge:raw',
        'home_page:bool',
        'in_menu:bool',
    ],
]) ?>
