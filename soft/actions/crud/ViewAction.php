<?php

namespace soft\actions\crud;

use soft\actions\base\ModelBaseAction;

class ViewAction extends ModelBaseAction
{

    public $view = 'view';

    public $title;

    public $footer;

    public function run()
    {
        parent::run();

        return $this->ajaxCrud->viewAction($this->model, [
            'view' => $this->view,
            'title' => $this->title,
            'footer' => $this->footer,
        ]);
    }

}
