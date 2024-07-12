<?php

namespace api\controllers;


use api\models\Banner;
use api\utils\MessageConst;
use common\modules\product\models\Brand;

class BrandController extends ApiBaseController
{
    /**
     * @return array
     */
    public function actionIndex(): array
    {
        return $this->success(Banner::find()->orderBy(['count' => SORT_ASC])->active()->all(), MessageConst::GET_SUCCESS);
    }
}