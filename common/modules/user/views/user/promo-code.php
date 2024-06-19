<?php

use common\models\User;
use common\modules\book\models\BookPromoCode;
use common\modules\book\models\search\BookPromoCodeSearch;
use soft\grid\GridView;
use soft\helpers\Html;
use yii\helpers\Url;

/* @var $model User */
/* @var $searchModel BookPromoCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Foydalanilgan promo kodlar. " . $model->fullname;
$this->addBreadCrumb("Foydalanuvchilar", 'index');
$this->addBreadCrumb($model->fullname, ['user/view', 'id' => $model->id]);
$this->addBreadCrumb("Foydalanilgan promo kodlar");

$this->registerAjaxCrudAssets();
?>

<?= $this->render('_tab-menu', ['model' => $model]) ?>


<?= GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{refresh}',
    'panel' => [
        'before' => "<i>Foydalanuvchi <b>{$model->fullname}</b> tomonidan foydalanilgan promo kodlar</i>",
    ],
    'toolbarButtons' => [
        'create' => [
            /** @see soft\widget\button\Button for other configurations */
            'modal' => false,
        ]
    ],
    'columns' => [
        [
            'attribute' => 'book_id',
            'format' => 'raw',
            'value' => function (BookPromoCode $model) {
                return Html::a($model->book ? $model->book->name : '', ['/book-manager/book/view', 'id' => $model->book_id], ['data-pjax' => 0]);
            }
        ],
        'promo_code',
        'use_at:datetime',
        'is_use:bool',
    ],
]); ?>

