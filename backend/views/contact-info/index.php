<?php

use common\models\ContactInfo;
use soft\grid\StatusColumn;

/* @var $this soft\web\View */
/* @var $searchModel common\models\search\ContactInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Sayt ma'lumotlari";
$this->params['breadcrumbs'][] = $this->title;
$this->registerAjaxCrudAssets();
?>
<code>!!!Tizimda oxirgi qo'shilgan ma'lumot ko'rinadi.</code>
<?= \soft\grid\GridView::widget([
    'id' => 'crud-datatable',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'toolbarTemplate' => '{create}{refresh}',
    'toolbarButtons' => [
        'create' => [
            /** @see soft\widget\button\Button for other configurations */
            'modal' => true,
        ]
    ],
    'columns' => [
//        'id',
        [
            'attribute' => 'imageUrl',
            'label' => "Rasm",
            'format' => ['image', ['width' => '40px']]
        ],
        'phone',
        'support_phone',
        [
            'class' => StatusColumn::class,
        ],
        //'created_by',
        //'updated_by',
        //'created_at',
        //'updated_at',
        'actionColumn' => [
            'viewOptions' => [
                'role' => 'modal-remote',
            ],
            'updateOptions' => [
                'role' => 'modal-remote',
            ],
        ],
    ],
]); ?>
    