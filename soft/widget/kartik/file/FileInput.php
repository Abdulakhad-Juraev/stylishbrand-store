<?php

namespace soft\widget\kartik\file;

use soft\helpers\ArrayHelper;

class FileInput extends \kartik\file\FileInput
{


    public function init()
    {
        $this->options = $this->renderOptions();
        $this->pluginOptions = $this->renderPluginOptions();
        parent::init();
    }

    /**
     * @return array
     */
    public function renderOptions(): array
    {
        return ArrayHelper::merge($this->options(), $this->options);
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }

    /**
     * @return array
     */
    public function renderPluginOptions(): array
    {
        return ArrayHelper::merge($this->pluginOptions(), $this->pluginOptions);
    }

    /**
     * @return array
     */
    public function pluginOptions()
    {
        return [];
    }

}
