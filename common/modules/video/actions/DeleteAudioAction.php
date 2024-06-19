<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 23-Mar-24, 21:32
 */

namespace common\modules\video\actions;

use common\modules\video\models\Video;
use Exception;
use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class DeleteAudioAction extends Action
{
    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     */
    public function run($id)
    {
        $model = Video::findModel($id);


        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->deleteFullOrgAudioSrc();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException($e->getMessage());
        }


        $transaction->commit();
        Yii::$app->session->setFlash('success', "Ushbu Postkast faylni o'chirish muvaffaqiyatli amalga oshirildi");
        return $this->controller->redirect(['view', 'id' => $model->id]);
    }
}