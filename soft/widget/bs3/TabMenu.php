<?php


namespace soft\widget\bs3;

use soft\base\SoftWidgetBase;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use soft\helpers\Html;
use soft\widget\adminlte2\BoxWidget;

class TabMenu extends SoftWidgetBase
{

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var bool
     */
    public $encodeLabels = true;

    /**
     * @var array Menu items
     *
     * Item elementlariga quyidagi optionlarni berish mumkin:
     * - label - string - Menu sarlavhasi
     * - linkOptions - array - Menu (a tegi uchun) optionlar,
     * - listOptions - array - Menu tashqarsidagi tag optionlari, default ['tag' => 'li']
     * - slideOptions - array - Menuning pastki qismidagi slide uchun optionlar,
     * - url - string|array - Menu ssilkasi
     * - encodeLabel - bool- Labelni encode qilish kerakmi yoki yo'qmi? Bu options $this->encodeLabel xususiyatini override qilib yuboradi
     * - icon - string|array - Ikonka, masalan: string shaklida <i class="fa fa-info-circle"></i>
     *      yoki array shaklida ['class' => 'fa fa-info-circle'], bunda tag qiymatini ham berish mumkin, tag qiymatini default qiymati - "i"
     *
     *  - active - bool - Active yoki aktive emasligi, agar berilmasa, url linkka qarab aniqlanadi
     *  - count - string|array - Menu labelidan keyingi badge matni. Masalan: string shaklida `5`.
     *  Yoki array shaklida: ['danger' => 5, 20, 'success' => 10, 'warning' => 15]
     *  - countType - string - Menu labelidan keyingi badge stili (warning, primary..) Defaults to `default`.
     *  - visible - bool  - agar false bo'lsa menyuda ko'rinmaydi
     *
     * usage:
     *
     * ```php
     * 'items' => [
     *      [
     *          'label' => 'Update',
     *          'url' => ['update', 'id' => $id],
     *      ],
     *      [
     *          'label' => 'View',
     *          'url' => ['view', 'id' => $id],
     *      ]
     * ]
     * ```
     */
    public $items = [];

    /**
     * @var array
     */
    public $listContainerOptions = [];

    /**
     * @var string
     */
    public $activeLinkClass = 'btn btn-primary';

    /**
     * @var array
     */
    public $listOptions = [];

    /**
     * @var array
     */
    public $linkOptions = ['class' => 'tab-menu-link'];

    /**
     * @var string
     */
    public $right;

    /**
     * @var array
     */
    public $rightOptions = [];

    /**
     * {@inheritdoc}
     */
    public function run()
    {

        $this->registerClientScript();
        $this->options['id'] = $this->getId();
        $items = $this->renderItems();
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        Html::addCssClass($this->options, 'tab-menu');
        return Html::tag($tag, $items, $this->options);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function renderItems()
    {
        $items = "";
        foreach ($this->items as $item) {
            $items .= $this->renderItem($item);
        }

        $items .= $this->renderRight();
        return Html::tag('ul', $items, $this->listContainerOptions);
    }

    /**
     * @param $item array
     * @return string
     * @throws \Exception
     */
    public function renderItem($item)
    {

        if (!isset($item['url'])) {
            throw new InvalidArgumentException("Url must be specified");
        }

        if (isset($item['visible']) && $item['visible'] === false) {
            return "";
        }

        $isActive = $this->isItemActive($item);

        $linkContent = $this->renderLinkContent($item, $isActive);
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', $this->linkOptions);

        if ($isActive) {
            Html::addCssClass($linkOptions, $this->activeLinkClass);
        }

        $link = Html::a($linkContent, $item['url'], $linkOptions);

        $listOptions = ArrayHelper::getValue($item, 'listOptions', $this->listOptions);

        return Html::tag('li', $link, $listOptions);
    }

    /**
     * @param $item array
     * @param bool $isActive
     * @return string
     * @throws \Exception
     */
    public function renderLinkContent($item, $isActive = false)
    {
        $label = ArrayHelper::getValue($item, 'label', '');

        if (isset($item['encodeLabel']) && $item['encodeLabel']) {
            $label = Html::encode($label);
        } elseif ($this->encodeLabels) {
            $label = Html::encode($label);
        }

        $icon = $this->renderIcon($item);
        $badge = $this->renderCount($item, $isActive);

        return $icon . " " . $label . " " . $badge;

    }

    /**
     * Checks whether a menu item is active.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return bool whether the menu item is active
     */
    public function isItemActive($item)
    {
        if (isset($item['active'])) {
            return $item['active'];
        }
        if (isset($item['url'])) {
            $item['url'] = (array)$item['url'];
            if (isset($item['url'][0])) {
                $route = Yii::getAlias($item['url'][0]);
                if (strpos($route, '/') !== 0 && Yii::$app->controller) {
                    $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
                }
                if (ltrim($route, '/') === Yii::$app->controller->route) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $item array
     * @return string
     * @throws \Exception
     */
    public function renderIcon($item)
    {

        $icon = ArrayHelper::getValue($item, 'icon');

        if (is_array($icon)) {
            $tag = ArrayHelper::remove($icon, 'tag', 'i');
            return Html::tag($tag, '', $icon);
        }

        return Html::icon($icon);

    }

    /**
     * @param $item array
     * @param $isActive bool
     * @return string
     * @throws \Exception
     */
    public function renderCount($item, $isActive = false)
    {

        if (empty($item['count'])) {
            return '';
        }
        $count = $item['count'];
        $countType = $item['countType'] ?? 'default';

        if ($isActive && $countType === 'primary') {
            $countType = 'default';
        }

        if (is_array($count)) {
            $result = '';
            foreach ($count as $key => $value) {

                if (is_integer($key)) {
                    $key = $countType;
                }
                if (!empty($value)) {

                    if ($isActive && $key === 'primary') {
                        $key = 'default';
                    }

                    $result .= Html::badge($value, $key) . ' ';
                }
            }
            return $result;
        } else {
            return Html::badge($count, $countType);
        }
    }

    /**
     * @return string
     */
    private function renderRight()
    {
        if (!$this->right) {
            return '';
        }

        Html::addCssClass($this->rightOptions, 'pull-right');
        return Html::tag('li', $this->right, $this->rightOptions);
    }

    /**
     * Registers the needed styles
     */
    private function registerClientScript()
    {
        $view = $this->getView();

        $css = <<<CSS

.tab-menu {
  position: relative;
  border-radius: 3px;
  background: #ffffff;
  width: 100%;
  box-shadow: 0 1px 1px rgba(0,0,0,0.1);
  padding: 7px;
  margin-bottom: 15px;
}

.tab-menu ul{
    padding: 0 3px;
    margin: 0;
}

.tab-menu ul li{
    padding-left: 5px;
    list-style-type: none;
    display: inline-block;
}

.tab-menu ul li:first-child{
    padding-left: 0;
}

.tab-menu ul li:last-child{
    padding-right: 0;
}

.tab-menu .tab-menu-link{
    display: inline-block;
    margin-bottom: 0;
    font-weight: normal;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    padding: 6px 10px;
    font-size: 14px;
    line-height: 1.42857143;
    border-radius: 4px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

CSS;

        $view->registerCss($css, [], 'tab-menu-css');
    }


}
