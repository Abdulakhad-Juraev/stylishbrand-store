<?php


/* @var $this soft\web\View */

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $model backend\modules\usermanager\models\User */

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="card card-primary">
    <div class="card-body">
        <p>
            <?= Html::a('<i class="fas fa-pen"></i> ' . Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Ushbu elementni o\'chirmoqchimisiz?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'username',
                'fullname',

                [
                    'attribute' => 'type_id',
                    'format' => 'raw',
                    'value' => function (User $model) {
                        if ($model->type_id == User::TYPE_ID_ADMIN) {
                            return '<span class="badge badge-danger">' . 'Admin' . '</span>';
                        } elseif ($model->type_id == User::TYPE_ID_EDITOR) {
                            return '<span class="badge badge-success">' . 'Muharrir' . '</span>';
                        } elseif ($model->type_id == User::TYPE_ID_JOURNALIST) {
                            return '<span class="badge badge-warning">' . 'Jurnalist' . '</span>';
                        }
                    }
                ],
                'created_at:dateTime',
            ],
        ]) ?>
    </div>
</div>
