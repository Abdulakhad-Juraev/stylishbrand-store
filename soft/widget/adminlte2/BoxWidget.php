<?php

namespace soft\widget\adminlte2;

use soft\base\SoftWidgetBase;
use soft\helpers\ArrayHelper;
use soft\helpers\Html;

class BoxWidget extends SoftWidgetBase
{

    /**
     * @var string
     */
    public $type = self::TYPE_PRIMARY;

    /**
     * @var string|false|null if false, header will not be rendered.
     */
    public $header;

    /**
     * @var array
     */
    public $headerOptions = [];

    /**
     * @var int from 1 to 12
     */
    public $colMd;

    /**
     * @var bool
     */
    public $withBorder = true;

    /**
     * @var bool
     */
    public $solid = false;

    /**
     * @var string
     */
    public $title;

    /**
     * @var array
     */
    public $titleOptions = ['tag' => 'h3'];

    /**
     * @var string
     */
    public $boxTools;

    /**
     * @var array
     */
    public $boxToolsOptions = [];

    /**
     * @var string
     */
    public $body;

    /**
     * @var array
     */
    public $bodyOptions = [];

    /**
     * @var string
     */
    public $footer;

    /**
     * @var array
     */
    public $footerOptions = [];

    public function init()
    {
        if (!$this->visible) {
            return;
        }
        parent::init();

        if ($this->colMd) {
            echo Html::beginTag('div', ['class' => 'col-md-' . $this->colMd]);
        }
        echo $this->beginWidget();
        echo $this->renderHeader();
        echo $this->beginBody();
        echo $this->body;
    }

    private function beginWidget()
    {
        Html::addCssClass($this->options, 'box');
        if ($this->type) {
            Html::addCssClass($this->options, 'box-' . $this->type);
        }
        if ($this->solid) {
            Html::addCssClass($this->options, 'box-solid');
        }
        return Html::beginTag('div', $this->options);
    }

    public function run()
    {
        echo Html::endTag('div'); // end body
        echo $this->renderFooter();
        echo Html::endTag('div'); // end widget
        if ($this->colMd) {
            echo Html::endTag('div');
        }
    }

    private function renderHeader()
    {
        if ($this->header === false) {
            return '';
        }

        if (empty($this->title) && empty($this->boxTools)) {
            return '';
        }

        $header = $this->header;
        $header .= $this->renderTitle();
        $header .= $this->renderBoxTools();
        Html::addCssClass($this->headerOptions, ['box-header']);
        if ($this->withBorder) {
            Html::addCssClass($this->headerOptions, ['with-border']);

        }
        return Html::tag('div', $header, $this->headerOptions);
    }

    private function renderTitle()
    {
        if ($this->title) {
            Html::addCssClass($this->titleOptions, 'box-title');
            $titleTag = ArrayHelper::remove($this->titleOptions, 'tag', 'h3');
            return Html::tag($titleTag, $this->title, $this->titleOptions);
        }
        return '';
    }

    private function renderBoxTools()
    {
        if ($this->boxTools) {
            Html::addCssClass($this->boxToolsOptions, 'box-tools pull-right');
            $boxToolsTag = ArrayHelper::remove($this->boxToolsOptions, 'tag', 'div');
            return Html::tag($boxToolsTag, $this->boxTools, $this->boxToolsOptions);
        }
        return '';
    }

    private function beginBody()
    {
        Html::addCssClass($this->bodyOptions, ['class' => 'box-body']);
        return Html::beginTag('div', $this->bodyOptions);
    }

    private function renderFooter()
    {
        if (!$this->footer){
            return '';
        }

        Html::addCssClass($this->footerOptions, 'box-footer');
        return Html::tag('div', $this->footer, $this->footerOptions);
    }


}
