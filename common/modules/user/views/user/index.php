<?php

use common\modules\user\models\User;
use yii\bootstrap4\Html;

/* @var $this soft\web\View */
/* @var $searchModel common\modules\user\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Foydalanuvchilar';
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();

?>
<?= \soft\grid\GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}',
    'toolbarButtons' => [
        'create' => [
            'modal' => false,
            'pjax' => false,
            'cssClass' => 'btn btn-info',
            'content' => "Yangi qo'shish",
            'icon' => 'user-plus,fas'
        ]
    ],
    'columns' => [
        [
            'attribute' => 'imageUrl',
            'label' => Yii::t('app', 'Image'),
            'format' => ['image', ['width' => '60px']],
        ],
        'id',
        [
            'class' => \soft\grid\ViewLinkColumn::class,
            'attribute' => 'username',
//            'format' => 'raw',
//            'value' => function ($model) {
//                return Html::a($model->username, ['user-device/index', 'id' => $model->id], ['data-pjax' => "0", 'title' => "Foydalanuvchi qurilmalari"]);
//            }
        ],
        'firstname',
        'lastname',
        [
            'attribute' => 'status',
            'filter' => User::statuses(),
            'format' => 'raw',
            'value' => function ($model) {
                /** @var User $model */
                return $model->statusBadge;
            }
        ],
        [
            'label' => 'Qurilmalar',
            'format' => 'raw',
            'value' => function ($model) {
                /** @var User $model */
                return $model->activeDevicesCount . ' / ' . $model->devicesCount;
            }
        ],
//        [
//            'attribute' => 'is_blocked',
//            'format' => 'raw',
//            'filter' => User::blockeds(),
//            'value' => 'blockName',
//        ],

        'created_at',
        'actionColumn' => [
            'template' => "{view} {update} {delete}",
            'buttons' => [
                'fill-balance' => function ($url, $model) {

                    /** @var User $model */
                    return \soft\widget\button\Button::widget([
                        'url' => $url,
                        'icon' => 'funnel-dollar,fas',
                        'title' => 'Hisobni to\'ldirish',
                        'modal' => true,
                    ]);

                }
            ],
            'visibleButtons' => [
                'delete' => function (User $model) {
                    return !$model->getIsDeleted();
                },
                'update' => function (User $model) {
                    return !$model->getIsDeleted();
                },
                'fill-balance' => function (User $model) {
                    return !$model->getIsDeleted();
                }
            ]
        ],
    ],
]); ?>
