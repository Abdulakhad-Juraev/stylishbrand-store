<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 30.11.2021, 13:22
 */

namespace backend\modules\regionmanager\actions;

use backend\modules\regionmanager\models\District;
use backend\modules\regionmanager\models\Quarter;
use Yii;
use yii\web\Response;

class QuartersAction extends \yii\base\Action
{


    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $district_id = $parents[0];
                if ($district_id) {
                    $quarters = Quarter::find()->orderBy('name asc')->where(['district_id' => $district_id])->all();
                    foreach ($quarters as $quarter) {
                        $out[] = [
                            'id' => $quarter->id,
                            'name' => $quarter->name
                        ];
                    }
                }
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];

    }

}
