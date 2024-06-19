<?php


namespace soft\widget\adminlte3;


use soft\base\SoftWidgetBase;
use soft\helpers\ArrayHelper;
use soft\helpers\Html;

class InfoBoxWidget extends SoftWidgetBase
{

    const TYPE_INFO = 'bg-info';
    const TYPE_SUCCESS = 'bg-success';
    const TYPE_WARNING = 'bg-warning';
    const TYPE_DANGER = 'bg-danger';
    const TYPE_PRIMARY = 'bg-primary';

    const SHADOW_DEFAULT = '';
    const SHADOW_NONE = 'shadow-none';
    const SHADOW_SM = 'shadow-sm';
    const SHADOW_MD = 'shadow';
    const SHADOW_LG = 'shadow-lg';

    public $shadow = self::SHADOW_LG;

    /**
     * @var string
     * @see \soft\helpers\Html::icon()
     */
    public $icon = 'bookmark,far';

    public $iconOptions = [];

    public $iconBackground = self::TYPE_PRIMARY;

    public $contentOptions = [];

    public $text;

    public $textOptions = [];

    public $encodeText = false;

    public $number;

    public $numberOptions = [];

    public $encodeNumber = false;


    public function renderWidgetContent()
    {
        $infoBoxContent = $this->renderInfoBoxContent();
        $icon = $this->renderIcon();
        Html::addCssClass($this->options, 'info-box ');
        if ($this->shadow) {
            Html::addCssClass($this->options, $this->shadow);
        }
        return $icon . "\n" . $infoBoxContent;
    }

    private function renderInfoBoxContent()
    {
        $text = $this->renderText();
        $number = $this->renderNumber();
        $tag = ArrayHelper::remove($this->contentOptions, 'tag', 'div');
        Html::addCssClass($this->contentOptions, 'info-box-content');
        return Html::tag($tag, $text . "\n" . $number, $this->contentOptions);
    }

    private function renderText()
    {
        $tag = ArrayHelper::remove($this->textOptions, 'tag', 'span');
        Html::addCssClass($this->textOptions, 'info-box-text');
        $text = $this->encodeText ? Html::encode($this->text) : $this->text;
        return Html::tag($tag, $text, $this->textOptions);

    }

    private function renderNumber()
    {
        $tag = ArrayHelper::remove($this->numberOptions, 'tag', 'span');
        Html::addCssClass($this->numberOptions, 'info-box-number');
        $number = $this->encodeNumber ? Html::encode($this->number) : $this->number;
        return Html::tag($tag, $number, $this->numberOptions);
    }

    private function renderIcon()
    {
        if (!$this->icon) {
            return '';
        }
        Html::addCssClass($this->iconOptions, 'info-box-icon');
        if ($this->iconBackground) {
            Html::addCssClass($this->iconOptions, $this->iconBackground);
        }
        $tag = ArrayHelper::remove($this->iconOptions, 'tag', 'span');
        $icon = Html::icon($this->icon);;
        return Html::tag($tag, $icon, $this->iconOptions);
    }


}