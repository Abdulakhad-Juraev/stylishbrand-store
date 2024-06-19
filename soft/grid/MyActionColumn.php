<?php
/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 18.05.2022, 10:11
 */

namespace soft\grid;

class MyActionColumn extends ActionColumn
{


    public $visibleButtons = [];

    public $buttons = [];

    public function init()
    {
        if (empty($this->visibleButtons)) {
            $this->visibleButtons = $this->visibleButtons();
        }

        if (empty($this->buttons)) {
            $this->buttons = $this->buttons();
        }
        parent::init();
    }

    /**
     * @return array
     */
    public function visibleButtons(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function buttons():array
    {
        return [];
    }

}
