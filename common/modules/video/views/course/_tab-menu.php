<?php


use common\modules\video\models\Video;
use soft\web\View;
use soft\widget\bs4\TabMenu;

/* @var $this View */
/* @var $model Video */

?>


<?= TabMenu::widget([

    'items' => [

        [
            'label' => 'Kurs haqida',
            'url' => ['course/view', 'id' => $model->id],
            'icon' => 'question-circle,far',
        ],

        [
            'label' => ' Vazifalar',
            'url' => ['course/homework', 'id' => $model->id],
            'icon' => 'tasks,fas',
            'visible' => $model->serial_type_id == Video::SERIAL_TYPE_PART
        ],
        [
            'label' => "Modullar",
            'url' => ['course/seasons', 'id' => $model->id],
            'visible' => $model->serial_type_id == Video::SERIAL_TYPE_SERIAL,
            'icon' => 'file-alt,far',
        ],
        [
            'label' => 'Modul darslari',
            'url' => ['course/parts', 'id' => $model->id],
            'visible' => $model->serial_type_id == Video::SERIAL_TYPE_SERIAL,
            'icon' => 'list-ol,fas',
        ],
        [
            'label' => ' Kurs sifatlari',
            'url' => ['course/options', 'id' => $model->id],
            'icon' => 'circle,fas',
            'visible' => $model->serial_type_id == Video::SERIAL_TYPE_SERIAL,
        ],
        [
            'label' => ' Kursdan keyin va oldin',
            'url' => ['course/before-afters', 'id' => $model->id],
            'icon' => 'circle,fas',
            'visible' => $model->serial_type_id == Video::SERIAL_TYPE_SERIAL,
        ],
        [
            'label' => " Qo'shimcha sifatlar",
            'url' => ['course/video-additional-option', 'id' => $model->id],
            'icon' => 'list,fas',
            'visible' => $model->serial_type_id == Video::SERIAL_TYPE_SERIAL,
        ],
        [
            'label' => 'Fikrlar',
            'url' => ['course/video-comment', 'id' => $model->id],
            'icon' => 'comments,far',
            'visible' => $model->serial_type_id == Video::SERIAL_TYPE_SERIAL,
        ],

//        [
//            'label' => "Ko'rganlar",
//            'url' => ['course/views', 'id' => $model->id],
//            'icon' => 'eye',
//        ],
    ]

]) ?>
