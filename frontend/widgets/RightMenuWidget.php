<?php

namespace frontend\widgets;

use frontend\models\Menu;
use yii\base\Widget;

class RightMenuWidget extends Widget
{

    public $type;

    public $value;

    public $view = '@frontend/views/_partials/right-menu';

    public function run()
    {
        $menu = $this->findMenu();
        return $this->render($this->view, [
            'menu' => $menu,
        ]);

    }

    /**
     * @return \common\modules\menu\models\Menu|null
     */
    private function findMenu()
    {

        $menu = Menu::find()
            ->andWhere([
                'url_type' => $this->type,
                'url_value' => $this->value,
                'status' => 1,
                'active' => 1,
                'disabled' => 0,
                'root' => 1
            ])
            ->cache()
            ->one()
        ;
        if ($menu) {
            return $menu->lvl == 2 ? $menu->parent : $menu;
        }

        return null;
    }

}
