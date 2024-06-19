<?php

namespace soft\widget\button;

use Yii;
use soft\helpers\Html;


class BulkButtons extends Buttons
{

    /**
     * @var array buttons  - button name and options for BulkButton() widget
     * @see BulkButton
     * @example
     *  [
     *      'view' => [
     *          // options for BulkButton widget
     *          'label' => "View",
     *           ...
     *      ],
     *      'update' => [
     *          'label' => "Update",
     *           ...
     *      ],
     *
     * ]
     */
    public $buttons = [];

    public $group = false;

    public $text;

    public $icon = 'hand-point-right,far';

    public $layout = '
    	<div class="pull-left">
				{text}:&nbsp;
				{icon}&nbsp;
				{buttons}
			</div><div class="clearfix"></div>';


    public function renderWidgetContent()
    {
        $buttons = parent::renderWidgetContent();
        $icon = Html::icon($this->icon);
        return strtr($this->layout, [

            '{text}' => $this->text == null ? Yii::t('site', 'With selected') : $this->text,
            '{icon}' => $icon,
            '{buttons}' => $buttons,

        ]);
    }



    public function renderButton($buttonConfig)
    {
        return BulkButton::widget($buttonConfig);
    }

}