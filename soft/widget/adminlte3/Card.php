<?php

namespace soft\widget\adminlte3;

use yii\helpers\Html;

class Card extends \soft\widget\bs4\Card
{

    public $outline = true;

    public function init()
    {
        if ($this->outline){
            Html::addCssClass($this->options, 'card-outline');
        }
        parent::init();
    }


}