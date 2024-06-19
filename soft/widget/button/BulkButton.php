<?php


namespace soft\widget\button;

use Yii;
use soft\helpers\Html;

class BulkButton extends Button
{

    public $confirmTitle;

    public $confirmMessage;

    public $smallButton = true;

    public function normalizeOptions()
    {
        parent::normalizeOptions();

        $this->options['role'] = 'modal-remote-bulk';
        $this->options['data-confirm'] = false;
        $this->options['data-request-method'] = 'post';
        $this->options['data-confirm-title'] = $this->confirmTitle == null ? Yii::t('site', 'Are you sure') : $this->confirmTitle ;
        $this->options['data-confirm-message'] = $this->confirmMessage == null ? Yii::t('site', 'Do you confirm this action?') : $this->confirmMessage ;

        if ($this->smallButton){
            Html::addCssClass($this->options, 'btn-sm');
        }

    }


}