<?php

namespace soft\widget\button;

use soft\base\SoftWidgetBase;
use soft\extra\Toggle;
use soft\helpers\Html;
use soft\helpers\ArrayHelper;

class Button extends SoftWidgetBase
{

    const TYPE_LINK = 'link';
    const TYPE_BUTTON = 'button';
    const TYPE_SUBMIT = 'submit';

    /**
     * @var string class for button
     */
    public $cssClass;

    public $type = self::TYPE_LINK;

    public $url;

    public $content;

    /**
     * @var string|array icon for button
     * @see Html::icon()
     */
    public $icon;

    /**
     * @var string title value
     */
    public $title;

    /**
     * @var string
     */
    public $toggle = Toggle::TYPE_TOOLTIP;

    /**
     * @var array
     */
    public $toggleOptions = [];

    /**
     * @var bool agar false bo'lsa,  'self::options[]' ga 'data-pjax' => 0 element qo'shiladi
     */
    public $pjax = true;

    /**
     * @var bool agar  true  bo'lsa,  'self::options[]' ga 'role' => 'modal-remote' element qo'shiladi
     */
    public $modal = false;

    public function run()
    {
        if (!$this->visible){
            return '';
        }
        echo $this->renderButton();
    }

    /**
     * Renders a button
     * @return string
     * @throws \Exception
     */
    public function renderButton()
    {

        $this->normalizeOptions();

        $content = $this->renderButtonContent();

        if ($this->type == self::TYPE_BUTTON) {

            return Html::button($content, $this->options);

        } elseif ($this->type == self::TYPE_SUBMIT) {

            return Html::submitButton($content, $this->options);

        } else {

            return Html::a($content, $this->url, $this->options);

        }

    }

    public function normalizeOptions()
    {

        $this->options['id'] = $this->getId();

        if (!empty($this->cssClass)) {
            Html::addCssClass($this->options, $this->cssClass);
        }

        if (!empty($this->title)) {
            $this->options['title'] = $this->title;
        }

        if (!empty($this->toggle) && !empty($this->title)) {
            $this->options = Toggle::addToggleOptions($this->options, $this->toggle, $this->toggleOptions);
        }

        if ($this->modal) {
            $this->options['role'] = "modal-remote";
        }

        if (!$this->pjax) {
            $this->options['data-pjax'] = 0;
        }

    }

    private function renderButtonContent()
    {
        return Html::withIcon($this->content, $this->icon);
    }


}
