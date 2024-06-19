<?php


namespace backend\modules\click\models;

use backend\modules\click\components\ClickData;
use common\modules\order\models\Order;
use common\modules\user\models\User;
use Yii;

class ClickUrlGenerator extends \yii\base\Model
{
    /**
     * @var int Pul miqdori (tiyinda).
     */
    public $amount;

    public $baseUrl = 'https://my.click.uz/services/pay';

    public $orderId;


    public function rules()
    {
        return [
            [['orderId'], 'required'],
            ['amount', 'integer'],
            ['userId', 'checkUser'],
        ];
    }

    /**
     * @param $attribute
     */
    public function checkUser($attribute)
    {
//        if (!User::find()->active()->andWhere(['id' => $this->orderId])->exists()) {
//            $this->addError($attribute, "Foydalanuvchi topilmadi!");
//        }
    }

    /**
     * @param $orderId
     * @return string
     */
    public function generateUrl($orderId)
    {
        $this->orderId = $orderId;
        $this->amount = Order::findOne($orderId)->price;
        $params = $this->generateParams();
        return $this->baseUrl . '?' . http_build_query($params);
    }

    /**
     * @return array
     */
    public function generateParams()
    {
        return [
            'service_id' => ClickData::SERVICE_ID,
            'merchant_id' => ClickData::MERCHANT_ID,
            'amount' => $this->amount,
            'transaction_param' => $this->orderId,
        ];
    }

}
