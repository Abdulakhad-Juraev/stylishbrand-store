<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 08.07.2021, 13:53
 */

namespace soft\widget\input;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * Class VisiblePasswordInput.
 * This widget customized for Bootstrap 4
 * @package soft\widget\input
 */
class VisiblePasswordInput extends InputWidget
{

    const INPUT_TEXT = 'text';
    const INPUT_PASSWORD = 'password';

    public $containerOptions = [];

    public $defaultInput = self::INPUT_PASSWORD;

    public $showText = "<i class='far fa-eye'></i>";
    public $hideText = "<i class='far fa-eye-slash'></i>";

    public $addonContainerOptions = [];
    public $addonTextOptions = [];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!in_array($this->defaultInput, [self::INPUT_PASSWORD, self::INPUT_TEXT])) {
            $this->defaultInput = self::INPUT_PASSWORD;
        }
        if (!isset($this->containerOptions['id'])) {
            $this->containerOptions['id'] = $this->options['id'] . '-container';
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        Html::addCssClass($this->options, 'vp-input');
        $input = $this->renderInputHtml($this->defaultInput);
        $addon = $this->renderAddon();
        $this->registeAssets();
        Html::addCssClass($this->containerOptions, 'input-group vp-container');
        return Html::tag('div', $input . "\n" . $addon, $this->containerOptions);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function renderAddon()
    {
        $tag = ArrayHelper::remove($this->addonContainerOptions, 'tag', 'div');
        $addonClass = isBs3() ? 'input-group-addon' : 'input-group-append';
        Html::addCssClass($this->addonContainerOptions, [$addonClass, 'vp-append']);
        $addonText = $this->renderAddonText();
        return Html::tag($tag, $addonText, $this->addonContainerOptions);
    }

    /**
     * @return string
     */
    private function renderAddonText()
    {
        $tag = ArrayHelper::remove($this->addonTextOptions, 'tag', 'span');
        Html::addCssClass($this->addonTextOptions, ['input-group-text', 'vp-addon-text']);
        $icon = $this->defaultInput == self::INPUT_PASSWORD ? $this->showText : $this->hideText;
        return Html::tag($tag, $icon, $this->addonTextOptions);
    }

    /**
     * Registers scripts
     */
    private function registeAssets()
    {
        $this->registerStyles();
        $this->registerScripts();
    }

    /**
     * Registers css style
     */
    private function registerStyles()
    {
        $css = <<<CSS
        .vp-append{
            cursor: pointer;
        }
CSS;
        $this->view->registerCss($css, [], 'visible_password_input_styles');

    }

    /**
     * Registers js scripts
     */
    private function registerScripts()
    {
        $textInput = self::INPUT_TEXT;
        $passwordInput = self::INPUT_PASSWORD;
        $hideText = addslashes($this->hideText);
        $showText = addslashes($this->showText);
        $js = "$(document).on('click', '.vp-container .vp-append', function(){
            let input = $(this).parent().find('input.vp-input')
            let inputType = input.attr('type')
            let addonText = $(this).find('.vp-addon-text')
            if(inputType=='$passwordInput'){
                input.attr('type', '$textInput')
                addonText.html('$hideText')
            }else{
                input.attr('type', '$passwordInput')
                addonText.html('$showText')
            }
        })";

        $this->view->registerJs($js, View::POS_READY, 'vp_styles');
    }

}
