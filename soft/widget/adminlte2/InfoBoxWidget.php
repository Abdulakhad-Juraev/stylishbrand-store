<?php


namespace soft\widget\adminlte2;


use soft\base\SoftWidgetBase;
use soft\helpers\ArrayHelper;
use soft\helpers\Html;

class InfoBoxWidget extends SoftWidgetBase
{

    const TYPE_INFO = 'bg-aqua';
    const TYPE_SUCCESS = 'bg-green';
    const TYPE_WARNING = 'bg-yellow';
    const TYPE_DANGER = 'bg-red';
    const TYPE_PRIMARY = 'bg-aqua';

    /**
     * @var string
     * @see \soft\helpers\Html::icon()
     */
    public $icon = 'bookmark,far';

    public $iconOptions = [];

    public $type = self::TYPE_INFO;

    public $contentOptions = [];

    public $text;

    public $textOptions = [];

    public $encodeText = false;

    public $number;

    public $numberOptions = [];

    public $encodeNumber = false;

    public $fullBackground = false;

    /**
     * @return string
     */
    public function renderWidgetContent()
    {
        $infoBoxContent = $this->renderInfoBoxContent();
        $icon = $this->renderIcon();
        Html::addCssClass($this->options, 'info-box ');
        if ($this->fullBackground && $this->type) {
            Html::addCssClass($this->options, $this->type);
        }
        return $icon . "\n" . $infoBoxContent;
    }

    /**
     * @return string
     */
    private function renderInfoBoxContent()
    {
        $text = $this->renderText();
        $number = $this->renderNumber();
        $tag = ArrayHelper::remove($this->contentOptions, 'tag', 'div');
        Html::addCssClass($this->contentOptions, 'info-box-content');
        return Html::tag($tag, $text . "\n" . $number, $this->contentOptions);
    }

    /**
     * @return string
     */
    private function renderText()
    {
        $tag = ArrayHelper::remove($this->textOptions, 'tag', 'span');
        Html::addCssClass($this->textOptions, 'info-box-text');
        $text = $this->encodeText ? Html::encode($this->text) : $this->text;
        return Html::tag($tag, $text, $this->textOptions);

    }

    /**
     * @return string
     */
    private function renderNumber()
    {
        $tag = ArrayHelper::remove($this->numberOptions, 'tag', 'span');
        Html::addCssClass($this->numberOptions, 'info-box-number');
        $number = $this->encodeNumber ? Html::encode($this->number) : $this->number;
        return Html::tag($tag, $number, $this->numberOptions);
    }

    /**
     * @return string
     */
    private function renderIcon()
    {
        if (!$this->icon) {
            return '';
        }
        Html::addCssClass($this->iconOptions, 'info-box-icon');
        if ($this->type && !$this->fullBackground) {
            Html::addCssClass($this->iconOptions, $this->type);
        }
        $tag = ArrayHelper::remove($this->iconOptions, 'tag', 'span');
        $icon = Html::icon($this->icon);;
        return Html::tag($tag, $icon, $this->iconOptions);
    }

}
