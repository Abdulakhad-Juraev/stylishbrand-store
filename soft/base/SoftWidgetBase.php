<?php


namespace soft\base;

use soft\helpers\ArrayHelper;
use soft\helpers\Html;
use yii\base\Widget;

class SoftWidgetBase extends Widget implements BootstrapTypeInterface
{

    public $options = [];

    public $tag = 'div';

    public $visible = true;

    public $type;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->options['id'] = $this->getId();
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        if (!$this->visible) {
            echo '';
            return;
        }

        echo $this->renderWidget();
    }

    /**
     * @return string
     */
    public function renderWidget()
    {
        $content = $this->renderWidgetContent();
        $tag = ArrayHelper::remove($this->options, 'tag', $this->tag);
        return Html::tag($tag, $content, $this->options);
    }

    /**
     * @return string
     */
    public function renderWidgetContent()
    {
        return "";
    }

}
