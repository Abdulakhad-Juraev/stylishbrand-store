<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 21.10.2021, 14:29
 */

namespace soft\widget\button;

use soft\widget\button\Button;
use Yii;

/**
 * Confirmation button
 */
class ConfirmButton extends Button
{

    public $ajax = true;

    public $confirmTitle = 'Тасдиқлайсизми?';
    public $confirmMessage = 'Ушбу амални тасдиқлайсизми?';

    public $icon = 'check,fas';

    public $title = 'Тасдиқлаш';

    public function normalizeOptions()
    {
        if ($this->ajax){
            $this->options['role'] = 'modal-remote';
            $this->options['data-confirm'] = false;
            $this->options['data-method'] = false;
            $this->options['data-pjax'] = '0';
            $this->options['data-request-method'] = 'post';
            $this->options['data-confirm-title'] = $this->confirmTitle;
            $this->options['data-confirm-message'] = $this->confirmMessage;
        }
        else{
            $this->options['data-confirm'] = $this->confirmMessage;
            $this->options['data-method'] = 'post';
        }

        parent::normalizeOptions();
    }


    public function run()
    {
        if (!$this->visible) {
            return '';
        }
        return $this->renderButton();
    }
}
