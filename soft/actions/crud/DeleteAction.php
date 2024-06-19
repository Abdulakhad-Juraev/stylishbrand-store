<?php

namespace soft\actions\crud;

use soft\actions\base\ModelBaseAction;

class DeleteAction extends ModelBaseAction
{

    public function run()
    {
        parent::run();
        $model = $this->model;

        if (!$model->getIsDeletable()) {
            forbidden();
        }

        $model->delete();
        return $this->ajaxCrud->closeModalResponse();
    }

}
