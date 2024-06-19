<?php

namespace soft\widget\kartik\file;

use soft\helpers\ArrayHelper;
use soft\helpers\Html;

class SingleImageFileInput extends FileInput
{

    public $showInitialPreview = true;

    public $initialPreviewUrl;

    public $initialPreview;

    public function init()
    {
        $this->renderInitialPreview();
        parent::init();
    }

    public function options()
    {
        return [
            'multiple' => false,
            'accept' => 'image/*',
        ];
    }

    public function pluginOptions()
    {

        return [
            'multiple' => false,
            'showUpload' => false,
            'showCaption' => false,
            'showRemove' => true,
            'showCancel' => false,
            'browseLabel' => 'Rasmni tanlang...',
            'browseOptions' => [
                'class' => 'btn btn-primary',
            ],
            'removeClass' => 'btn btn-danger',
            'initialCaption' => 'Rasmni tanlang',
        ];

    }

    private function renderInitialPreview()
    {
        if (!$this->showInitialPreview) {
            return;
        }

        if ($this->initialPreview) {
            $this->pluginOptions['initialPreview'] = $this->initialPreview;
            return;
        }

        if ($this->initialPreviewUrl) {
            $this->pluginOptions['initialPreview'] = [
                Html::img($this->initialPreviewUrl, ['class' => 'file-preview-image']),
            ];
            return;
        }

        if ($this->hasModel()) {
            $this->pluginOptions['initialPreview'] = [
                Html::img($this->model->{$this->attribute}, ['class' => 'file-preview-image']),
            ];
            return;
        }

    }

}
