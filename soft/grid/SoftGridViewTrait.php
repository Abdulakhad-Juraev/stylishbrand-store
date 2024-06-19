<?php


namespace soft\grid;

use soft\helpers\Html;
use soft\helpers\Url;
use Yii;

/**
 * GridView uchun qo'shimcha methodlar
 * @package soft\grid
 */
trait SoftGridViewTrait
{


    //<editor-fold desc="Select pager count on toolbar">

    /**
     * Customized for adminty template
     **/

    public function getPageSizes()
    {
        return [
            20 => '20',
            50 => '50',
            100 => '100',
            200 => '200',
            500 => '500',
            1000 => '1000',
        ];
    }

    public function renderPagerDropdown()
    {

        $defaultPageSize = $this->getDefaultPageSize();
        $sizes = $this->getPageSizes();

        if (Yii::$app->request->get($this->_toggleDataKey) == 'all') {
            $label = t("All");
        } else {
            $label = $sizes[$defaultPageSize];
        }

        $dropDownToggle = a(Html::tag('b', $label) . ' <span class="caret"></span>', '#', [
            'class' => 'btn dropdown-toggle btn-default',
            'type' => 'button',
            'data-toggle' => 'dropdown',
            'aria-haspopup' => true,
            'aria-expanded' => true,
            'id' => $this->getId() . "-dropdownMenu",

        ]);

        return tag('div', $dropDownToggle . $this->renderPagerDropdownLinks(), ['class' => 'btn-group']);

    }

    public function renderPagerDropdownLinks()
    {
        $list = '';
        $sizes = $this->getPageSizes();
        unset($sizes[-1]);
        foreach ($sizes as $size => $label) {
            $link = Url::current(['per-page' => $size, $this->_toggleDataKey => null]);
            $a = a($label, $link, ['class' => 'dropdown-item']);
            $list .= tag('li', $a);
        }

//        Separated link
        $list .= '<li role="separator" class="divider"></li>';

//        `All` link
        $link = Url::current([$this->_toggleDataKey => 'all', 'per-page' => null]);
        $a = a('Barchasi', $link, ['class' => 'dropdown-item']);
        $list .= tag('li', $a);

        return tag('ul', $list, ['class' => 'dropdown-menu', ' aria-labelledby' => $this->getId() . "-dropdownMenu"]);
    }


    public function getDefaultPageSize()
    {
        $defaultPageSize = (int)Yii::$app->request->get('per-page', 20);
        $sizes = $this->getPageSizes();

        if (!isset($sizes[$defaultPageSize])) {
            $defaultPageSize = 20;
        }
        return $defaultPageSize;
    }
    //</editor-fold>


}
