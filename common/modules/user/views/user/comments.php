<?php

use common\models\User;

/* @var $this soft\web\View */
/* @var $model User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Foydalanuvchi yozgan fikrlari";
$this->addBreadCrumb("Foydalanuvchilar", 'index');
$this->addBreadCrumb($model->fullname, ['user/view', 'id' => $model->id]);
$this->addBreadCrumb($this->title);
?>
<?= $this->render('_tab-menu', ['model' => $model]) ?>
<?= \soft\grid\GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'toolbarTemplate' => '{refresh}',
    'toolbarButtons' => [
        'create' => false
    ],
    'columns' => [
        [
            'attribute' => 'film.name',
            'label' => 'Film nomi'
        ],
        'comment'
    ],
]); ?>
