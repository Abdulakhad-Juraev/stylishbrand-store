<?php


namespace soft\widget\adminlte3;

use soft\base\SoftWidgetBase;
use soft\helpers\ArrayHelper;
use soft\helpers\Html;

class SmallBoxWidget extends SoftWidgetBase
{

    const TYPE_INFO = 'bg-info';
    const TYPE_SUCCESS = 'bg-success';
    const TYPE_WARNING = 'bg-warning';
    const TYPE_DANGER = 'bg-danger';
    const TYPE_PRIMARY = 'bg-primary';

    public $type = self::TYPE_INFO;

    public $options = [
        'class' => 'small-box',
    ];


    public $innerOptions = ['class' => 'inner'];
    public $largeText;
    public $largeTextOptions = ['tag' => 'h3'];

    public $smallText;
    public $smallTextOptions = ['tag' => 'p'];

    public $icon = 'list-alt';

    public $iconOptions = [];

    public $footerText;
    public $footerLink = '#';
    public $footerIcon = '<i class="fa fa-arrow-circle-right"></i>';
    public $footerLinkOptions = ['class' => 'small-box-footer'];

    public function renderWidgetContent()
    {
        $inner = $this->renderInner();
        $icon = $this->renderIcon();
        $footer = $this->renderFooter();
        Html::addCssClass($this->options, $this->type);
        return $inner.$icon.$footer;
    }

    public function renderInner()
    {
        $largeText = '';
        $smallText = '';
        if ($this->largeText) {

            $tag = ArrayHelper::remove($this->largeTextOptions, 'tag', 'h3');
            $largeText = Html::tag($tag, $this->largeText, $this->largeTextOptions);

        }

        if ($this->smallText) {

            $tag = ArrayHelper::remove($this->smallTextOptions, 'tag', 'h3');
            $smallText = Html::tag($tag, $this->smallText, $this->smallTextOptions);

        }

        return Html::tag( 'div', $largeText . $smallText, $this->innerOptions);

    }

    private function renderIcon()
    {
        if (!$this->icon){
            return '';
        }
        $icon = Html::icon($this->icon, $this->iconOptions);;

        return Html::tag('div', $icon, ['class' => 'icon']);
    }

    private function renderFooter()
    {
        if (!$this->footerText){
            return '';
        }

        if ($this->footerIcon){
            $text =  $this->footerIcon." ".$this->footerText;
        }
        else{
            $text = $this->footerText;
        }

        if ($this->footerLink){

            return Html::a( $text, $this->footerLink, $this->footerLinkOptions);

        }
        else{
            return Html::tag('span', $text, $this->footerLinkOptions);
        }
    }


}