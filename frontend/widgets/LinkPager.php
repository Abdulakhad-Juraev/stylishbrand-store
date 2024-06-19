<?php

namespace frontend\widgets;

class LinkPager extends \yii\bootstrap4\LinkPager
{

    public $nextPageLabel = '<i class="fa fa-arrow-right"></i>';

    public $prevPageLabel = '<i class="fa fa-arrow-left"></i>';

    public $options = ['class' => 'pagination-wrapper'];

}
