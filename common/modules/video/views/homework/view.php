<?php


/* @var $this soft\web\View */
/* @var $model common\modules\video\models\Homework */

use common\modules\video\models\Homework;
use soft\helpers\Html;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Vazifa', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \soft\widget\bs4\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'title_uz',
        'title_ru',
//        'title_en',
        'file_url',
        'statusBadge:raw',
        'created_at',
        'createdBy.fullname',
        'updated_at',
    ],
]) ?>
