<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 23-Mar-24, 14:39
 */

namespace api\modules\profile\controllers;

use api\controllers\ApiBaseController;
use api\models\PurchaseViaAdmin;
use api\modules\profile\models\Tariff;
use api\modules\profile\models\TariffGroup;
use backend\modules\click\models\ClickUrlGenerator;
use common\components\payme\PaymeUrlGenerator;
use common\modules\order\models\Order;
use common\modules\uzum\models\UzumUrlGenerator;
use Yii;

class TariffController extends ApiBaseController
{
    /**
     * @var bool
     */
    public $authRequired = true;

    /**
     * @var string[]
     */
    public $authOnly = ['all', 'purchase-with-payme', 'purchase-with-click', 'purchase-via-admin', 'purchase-with-uzum'];

    /**
     * @var string[]
     */
    public $authOptional = ['all'];

    /**
     * @return array
     */
    public function actionAll()
    {
        $tariffGroups = TariffGroup::find()
            ->active()
            ->all();

        return $tariffGroups ? $this->success($tariffGroups) : $this->error("Ma'lumot topilmadi");
    }

    /**
     * @return array
     */
    public function actionPurchaseWithPayme()
    {
        $user = Yii::$app->user->identity;

        $tariffId = Yii::$app->request->get('tariffId');

        $tariff = Tariff::find()
            ->active()
            ->andWhere(['id' => $tariffId])
            ->one();

        if ($tariff && $tariff->group && $tariff->group->status == TariffGroup::STATUS_ACTIVE) {

            $order = $user->tariffOrderCreate($tariff, Order::$type_payme);

            if ($order) {

                $paymeUrlGeneratorModel = new PaymeUrlGenerator();

                $data = [
                    'url' => $paymeUrlGeneratorModel->generateUrl($order->id)
                ];

                return $this->success($data);
            }

        }

        return $this->error("Obunani sotib olib bo'lmaydi!");
    }

    /**
     * @return array
     */
    public function actionPurchaseWithClick()
    {
        $user = Yii::$app->user->identity;

        $tariffId = Yii::$app->request->get('tariffId');

        $tariff = Tariff::find()
            ->active()
            ->andWhere(['id' => $tariffId])
            ->one();

        if ($tariff && $tariff->group && $tariff->group->status == TariffGroup::STATUS_ACTIVE) {

            $order = $user->tariffOrderCreate($tariff, Order::$type_click);

            if ($order) {

                $clickUrlGeneratorModel = new ClickUrlGenerator();
                $clickUrlGeneratorModel->generateUrl($order->id);

                $data = [
                    'url' => $clickUrlGeneratorModel->generateUrl($order->id)
                ];

                return $this->success($data);

            }

        }

        return $this->error("Obunani sotib olib bo'lmaydi!");
    }

    /**
     * @return array
     */
    public function actionPurchaseViaAdmin()
    {
        $tariffId = Yii::$app->request->get('tariffId');

        $tariff = Tariff::find()
            ->active()
            ->andWhere(['id' => $tariffId])
            ->one();

        $model = new PurchaseViaAdmin([
            'owner_id' => $tariff->id,
            'user_id' => user('id'),
            'type_id' => PurchaseViaAdmin::$type_id_tariff,
            'status' => PurchaseViaAdmin::STATUS_INACTIVE
        ]);

        if (!$tariff && !$tariff->group && $tariff->group->status != TariffGroup::STATUS_ACTIVE) {
            return $this->error("Obuna topilmadi!");
        }

        if ($model->load(Yii::$app->request->post(), '') && $model->validate() && $model->save()) {
            return $this->success();
        }

        return $this->error($model->firstErrorMessage);
    }

    /**
     * @return array
     */
    public function actionPurchaseWithUzum()
    {
        $user = Yii::$app->user->identity;

        $tariffId = Yii::$app->request->get('tariffId');

        $tariff = Tariff::find()
            ->active()
            ->andWhere(['id' => $tariffId])
            ->one();

        if ($tariff && $tariff->group && $tariff->group->status == TariffGroup::STATUS_ACTIVE) {

            /** @var Order $order */

            $order = $user->tariffOrderCreate($tariff, Order::$type_uzum);

            if ($order) {

                $uzumUrlGeneratorModel = new UzumUrlGenerator();
                $uzumUrlGeneratorModel->generateUrl($order->id);

                $data = [
                    'url' => $uzumUrlGeneratorModel->generateUrl($order->id)
                ];

                return $this->success($data);

            }

        }

        return $this->error("Obunani sotib olib bo'lmaydi!");
    }
}