<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 26.06.2021, 16:58
 */

namespace soft\widget\dynamicform;


use dosamigos\ckeditor\CKEditor;
use dosamigos\ckeditor\CKEditorWidgetAsset;
use yii\helpers\Json;

class CKEditorForDynamicForm extends CKEditor
{

    public $dynamicFormWidgetContainer = 'dynamicform_wrapper';

    public $dynamicFormWidgetBody = '.container-items';

    /**
     * @var string Hidden input for id.
     */
    public $hiddenInput = '.id-hidden-input';

    protected function registerPlugin()
    {
        $js = [];

        $view = $this->getView();

        CKEditorWidgetAsset::register($view);

        $id = $this->options['id'];

        $options = $this->clientOptions !== false && !empty($this->clientOptions)
            ? Json::encode($this->clientOptions)
            : '{}';

        $js[] = "CKEDITOR.replace('$id', $options);";
        $js[] = "dosamigos.ckEditorWidget.registerOnChangeHandler('$id');";

        if (isset($this->clientOptions['filebrowserUploadUrl']) || isset($this->clientOptions['filebrowserImageUploadUrl'])) {
            $js[] = "dosamigos.ckEditorWidget.registerCsrfImageUploadHandler();";
        }

        if ($this->dynamicFormWidgetBody && $this->dynamicFormWidgetContainer){
            $js[] = "$('.$this->dynamicFormWidgetContainer').on('afterInsert', function(e, item){
                let lastTextarea = $('$this->dynamicFormWidgetBody textarea').last()
                let lastHiddenInput = $('$this->dynamicFormWidgetBody $this->hiddenInput').last()
                lastHiddenInput.val('')
                let textareaId = lastTextarea.attr('id');
                lastTextarea.val('')
                lastTextarea.focus()
                CKEDITOR.replace(textareaId, $options);
            })";
        }

        $view->registerJs(implode("\n", $js));
    }

}