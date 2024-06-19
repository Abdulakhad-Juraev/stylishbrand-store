<?php
/*
 * @author Shukurullo Odilov <shukurullo0321@gmail.com>
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 26.03.2022, 10:06
 */

namespace soft\widget\kartik;

class Select2 extends \kartik\widgets\Select2
{

    public $placeholder;

    public $allowClear = true;

    public function init()
    {

        if (!isset($this->pluginOptions['allowClear'])) {
            $this->pluginOptions['allowClear'] = $this->allowClear;
        }
        if (!isset($this->pluginOptions['placeholder'])) {
            $placeholder = $this->placeholder === null ? 'Танланг...' : $this->placeholder;
            $this->pluginOptions['placeholder'] = $placeholder;
        }

        parent::init();
    }

}
