<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 25-Apr-24, 21:42
 */

namespace common\modules\uzum\models;

use common\modules\order\models\Order;
use soft\helpers\ArrayHelper;
use Yii;
use yii\base\Model;

class UzumUrlGenerator extends Model
{
    /**
     * @var int Pul miqdori (so'mda).
     */
    public $amount;

    /**
     * @var string
     */
    public $baseUrl = 'https://www.apelsin.uz/open-service';

    /**
     * @var
     */
    public $orderId;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['amount'], 'required'],
            ['amount', 'integer'],
            ['amount', 'checkAmount'],
//            ['orderId', 'checkOrder'],
        ];
    }

    /**
     * @param $attribute
     * @return void
     */
    public function checkAmount($attribute)
    {
        $amount = (int)$this->amount; //convert to sum from tiyin

        if ($amount < UzumData::$minAmount) {
            $this->addError($attribute, "Pul miqdori noto'g'ri");
        }
    }

    /**
     * @param $attribute
     */
    public function checkOrder($attribute)
    {
        if (!Order::find()->andWhere(['id' => $this->orderId])->exists()) {
            $this->addError($attribute, "Buyurtma  topilmadi!");
        }
    }

    /**
     * @return string
     */
    public function generateUrl($orderId)
    {
        $order = Order::findOne($orderId);
        $params = $this->generateParams($orderId, $order->price);
        return $this->baseUrl . '?' . http_build_query($params);
    }

    /**
     * @return array
     */
    public function generateParams($orderId, $price)
    {
        return [
//            'cash' => UzumData::$cash_id,
            'serviceId' => UzumData::$serviceId,
            'redirectUrl' => UzumData::$redirectUrl,
//            'description' => 'Номер заказа: ' . $orderId,
            'orderId' => $orderId,
            'amount' => $price * 100,
//            'transaction_id' => Yii::$app->security->generateRandomString(50) . $orderId,
//            'cabinetId' => $orderId,
        ];
    }

    /**
     * @return string the first error text of the model after validating
     * */
    public function getFirstErrorMessage()
    {
        $firstErrors = $this->firstErrors;
        if (empty($firstErrors)) {
            return null;
        }
        $array = array_values($firstErrors);
        return ArrayHelper::getArrayValue($array, 0, null);
    }
}