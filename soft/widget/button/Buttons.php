<?php


namespace soft\widget\button;


use soft\base\SoftWidgetBase;
use soft\extra\TemplateRender;
use yii\helpers\Html;

class Buttons extends SoftWidgetBase
{

    const SIZE_LARGE = 'btn-group-lg';
    const SIZE_SMALL = 'btn-group-sm';

    /**
     * @var array buttons  - button name and options for Button() widget
     * @see Button
     * @example
     *  [
     *      'view' => [
     *          // options for Button widget
     *          'label' => "View",
     *           ...
     *      ],
     *      'update' => [
     *          'label' => "Update",
     *           ...
     *      ],
     * ]
     */
    public $buttons = [];

    /**
     * @var string template for buttons
     * @see TemplateRender::$template
     */
    public $template = -1;

    /**
     * @var string separator between buttons
     * @see TemplateRender::$separator
     */
    public $separator = "&nbsp;";

    public $group = true;

    public $size = self::SIZE_LARGE;

    public $vertical = false;

    public function renderWidgetContent()
    {
       $this->normalizeButtons();
        return $this->renderButtons();
    }

    /**
     * @return array|string renders buttons
     * @throws \Exception
     * @see Button
     */

    public function renderButtons()
    {

        if (!is_array($this->buttons)) {
            return $this->buttons;
        }

        $buttons = [];
        foreach ($this->buttons as $buttonName => $buttonConfig) {
            $buttons[$buttonName] = $this->renderButton($buttonConfig);
        }

        $buttons = TemplateRender::widget([
            'template' => $this->template,
            'items' => $buttons,
            'separator' => $this->separator,
        ]);

        return $buttons;

    }

    public function normalizeButtons()
    {
        if ($this->group) {

            $class = $this->vertical ? "btn-group-vertical" : "btn-group";
            Html::addCssClass($this->options, $class);

            if ($this->size != null) {
                Html::addCssClass($this->options, $this->size);
            }
        }
    }

    public function renderButton($buttonConfig)
    {
        return Button::widget($buttonConfig);
    }

}