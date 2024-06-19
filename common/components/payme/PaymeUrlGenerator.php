<?php
/**
 * User: uluGbek
 * @author Ulugbek Muhammadjonov
 * @email <muhammadjonovulugbek98@gmail.com>
 * Date: 31.07.2023  15:52
 */

namespace common\components\payme;

use common\modules\order\models\Order;
use Yii;
use yii\base\Model;

class PaymeUrlGenerator extends Model
{
    /**
     * @var int Pul miqdori (tiyinda).
     */
    public $amount;

    public $baseUrl = 'https://checkout.paycom.uz';

    public $orderId;

    public function rules()
    {
        return [
            ['amount', 'integer'],
//            ['amount', 'checkAmount'],
        ];
    }

    /**
     * @param string $attribute
     */
    public function checkAmount($attribute)
    {
        $amount = (int)$this->amount / 100; //convert to sum from tiyin
        if ($amount < PaymeData::MIN_AMOUNT || $amount > PaymeData::MAX_AMOUNT) {
            $this->addError($attribute, "Pul miqdori noto'g'ri");
        }
    }


    /**
     * @param $orderId
     * @return string
     */
    public function generateUrl($orderId)
    {
        $order = Order::findOne($orderId);
        $params = $this->getParamsAsString($orderId, $order->price);
        return $this->baseUrl . '/' . base64_encode($params);
    }

    /**
     * @return array
     */
    public function generateParams($orderId, $totalSum)
    {
        return [
            'm' => PaymeData::$merchantId,
            'ac.order_id' => $orderId,
            'a' => $totalSum * 100,
            'l' => Yii::$app->language,
            'c' => Yii::$app->request->hostInfo,
            'cr' => 'UZS',
        ];
    }

    /**
     * @return string
     */
    public function getParamsAsString($orderId, $totalSum)
    {
        $params = $this->generateParams($orderId, $totalSum);

        $result = '';

        foreach ($params as $key => $value) {
            $result .= $key . '=' . $value . ';';
        }
        return rtrim($result, ';');
    }
}