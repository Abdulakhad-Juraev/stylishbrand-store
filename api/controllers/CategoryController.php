<?php

namespace api\controllers;


use Exception;
use api\models\Category;
use api\utils\MessageConst;

class CategoryController extends ApiBaseController
{
    /**
     * @return array
     */
    public function actionIndex()
    {
            return $this->success(Category::find()->orderBy(['id' => SORT_DESC])->active()->all(), MessageConst::GET_SUCCESS);
    }
}