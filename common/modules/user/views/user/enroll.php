<?php

use common\models\User;
use common\modules\book\models\BookPromoCode;
use common\modules\book\models\search\BookPromoCodeSearch;
use common\modules\user\models\Enroll;
use common\modules\user\models\search\EnrollSearch;
use soft\grid\GridView;
use yii\helpers\Url;

/* @var $model User */
/* @var $searchModel EnrollSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Azolik Kurslar. " . $model->fullname;
$this->addBreadCrumb("Foydalanuvchilar", 'index');
$this->addBreadCrumb($model->fullname, ['user/view', 'id' => $model->id]);
$this->addBreadCrumb("Azolik Kurslar");

$this->registerAjaxCrudAssets();
?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>


<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create} {refresh}',
    'panel' => [
        'before' => "<i>Foydalanuvchi <b>{$model->fullname}</b> tomonidan azolik kurslari</i>",
    ],
    'toolbarButtons' => [
        'create' => [
            /** @see soft\widget\button\Button for other configurations */
            'modal' => true,
            'url' => Url::to(['/user-manager/user-enroll/create', 'user_id' => $model->id])
        ]
    ],
    'columns' => [

        [
            'attribute' => 'video_id',
            'format' => 'raw',
            'value' => function (Enroll $model) {
                return $model->video ? $model->video->name : '';
            }
        ],
        'sold_price:sum',
        'real_price:sum',
        'end_at:date',

        [
            'attribute' => 'payment_type_id',
            'format' => 'raw',
            'filter' => Enroll::types(),
            'value' => function (Enroll $model) {
                return $model->types() ? $model->getTypeName() : '';
            }
        ],

        'had_subscription:bool',

        'actionColumn' => [
            'controller' => '/user-manager/user-enroll',
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],

    ],
]); ?>

