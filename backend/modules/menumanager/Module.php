<?php

namespace backend\modules\menumanager;

/**
 * menumanager module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\menumanager\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * Menu uchun sayt bo'limlari
     */
    public function sections()
    {
        return [
            'gallery/index' => "Fotolavhalar",
            '#portfolio' => "Bizning ishlarimiz",
            '#contact' => 'Biz bilan bog\'lanish',
            '#' => 'Boshqa',
        ];

    }
}
