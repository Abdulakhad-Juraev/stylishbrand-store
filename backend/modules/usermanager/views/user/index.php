<?php

use backend\modules\usermanager\models\User;
use soft\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var \common\models\User $dataProvider */
/** @var \common\models\User $searchModel */
$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>

        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'username',
                'fullname',
                [
                    'attribute' => 'type_id',
                    'format' => 'raw',
                    'filter' => User::types(),
                    'value' => function (User $model) {
                        if ($model->type_id == User::TYPE_ID_ADMIN) {
                            return '<span class="badge badge-danger">' . 'Admin' . '</span>';
                        } elseif ($model->type_id == User::TYPE_ID_EDITOR) {
                            return '<span class="badge badge-success">' . 'Muharrir' . '</span>';
                        } elseif ($model->type_id == User::TYPE_ID_DIRECTOR) {
                            return '<span class="badge badge-warning">' . 'Direktor' . '</span>';
                        }
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return date('d.m.Y', $data->created_at);
                    }
                ],
//        'updated_at',
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'width' => '120px',
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
