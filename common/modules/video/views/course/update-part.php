<?php

use common\modules\video\models\Video;
use soft\web\View;


/* @var $this View */
/* @var $model Video */


$parentModel = $model->parent;

$this->title = "Tahrirlash. " . $model->name . ' ' . $parentModel->name;
$this->params['breadcrumbs'][] = ['label' => 'Kurslar', 'url' => ['video/index']];
$this->params['breadcrumbs'][] = ['label' => $parentModel->name, 'url' => ['index', 'id' => $parentModel->id]];
$this->params['breadcrumbs'][] = ['label' => "Modul darslari", 'url' => ['course/parts', 'id' => $parentModel->id]];
$this->addBreadCrumb($model->name, ['view', 'id' => $model->id]);
$this->params['breadcrumbs'][] = "Tahrirlash";

?>

<?= $this->render('_form-part', ['model' => $model]) ?>

