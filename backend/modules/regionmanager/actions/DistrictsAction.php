<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 30.11.2021, 13:22
 */

namespace backend\modules\regionmanager\actions;

use backend\modules\regionmanager\models\District;
use Yii;
use yii\web\Response;

class DistrictsAction extends \yii\base\Action
{


    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $region_id = $parents[0];
                if ($region_id) {
                    $districts = District::find()->orderBy('name_uz asc')->where(['region_id' => $region_id])->all();
                    foreach ($districts as $district) {
                        $out[] = [
                            'id' => $district->id,
                            'name' => $district->name
                        ];
                    }
                }
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];

    }

}
