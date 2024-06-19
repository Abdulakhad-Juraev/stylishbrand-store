<?php


/* @var $this soft\web\View */
/* @var $model Video */
/* @var $parentModel Video*/

use common\modules\video\models\Video;

$this->title = "Yangi dars. " . $parentModel->name;
$this->params['breadcrumbs'][] = ['label' => 'Kurslar', 'url' => ['course/index']];
$this->params['breadcrumbs'][] = ['label' => $parentModel->name, 'url' => ['index', 'id' => $parentModel->id]];
$this->params['breadcrumbs'][] = "Yangi dars qo'shish";
?>

<?= $this->render('_form-part', [
    'model' => $model,
    'parentModel' => $parentModel,
]) ?>

