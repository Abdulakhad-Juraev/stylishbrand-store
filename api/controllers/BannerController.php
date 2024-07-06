<?php

namespace api\controllers;


use api\models\Banner;
use api\utils\MessageConst;

class BannerController extends ApiBaseController
{
    /**
     * @return array
     */
    public function actionIndex(): array
    {
        return $this->success(Banner::find()->active()->orderBy(['id' => SORT_DESC])->all(), MessageConst::GET_SUCCESS);
    }
}