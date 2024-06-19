<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 28-Mar-24, 16:45
 */

namespace common\modules\video\actions;

use common\modules\video\models\Video;
use Yii;
use yii\base\Action;
use yii\web\ServerErrorHttpException;

class DeleteVideoAction extends Action
{
    /**
     * @throws \yii\web\ForbiddenHttpException
     * @throws \Yii\web\NotFoundHttpException
     * @throws \yii\web\ServerErrorHttpException|\yii\db\Exception
     */
    public function run($id)
    {
        $model = Video::findModel($id);


//        if ($model->isStreaming()) {
//
//            forbidden("Diqqat!. Hozirda ushbu video qayta ishlanmoqda. Shu sababli filmni o'chirishga ruxsat berilmaydi!
//            Birozdan so'ng qayta urinib ko'ring! ");
//        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->deleteFullOrgSrc();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException($e->getMessage());
        }

        try {
            $model->deleteFullStream();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException($e->getMessage());
        }

        $transaction->commit();
        Yii::$app->session->setFlash('success', "Ushbu video faylni o'chirish muvaffaqiyatli amalga oshirildi");
        return $this->controller->redirect(['view', 'id' => $model->id]);
    }
}