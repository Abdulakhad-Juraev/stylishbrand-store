<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 30.12.2021, 10:00
 */

namespace common\components\payme;

use common\modules\order\models\Order;

class PaymeResponse extends \shoxabbos\paymeuz\PaymeResponse
{


    /**
     * Error. Order is waiting...
     */
    const NO_AUTH = -32504;

    /**
     * Error. Order is waiting...
     */
    const ORDER_IS_WAITING = -31099;

    /**
     * Error. Order is already paid
     */
    const ORDER_IS_ALREADY_PAID = -31098;

    /**
     * Error. Amount not enough
     */
    const AMOUNT_NOT_ENOUGH = -31097;

    /**
     * Error while saving order data
     */
    const ERROR_WHILE_SAVING_ORDER = -31096;


    /**
     * @param $code
     * @param $message
     * @param $data
     * @return array
     */
    public function error($code, $message = [], $data = null)
    {
        if (empty($message)) {
            $message = $this->getCustomErrorMessages($code);
        }
        return parent::error($code, $message, $data);
    }

    /**
     * @param $code
     * @return array|string[]
     */
    public function getCustomErrorMessages($code)
    {
        $messages = [

            self::ORDER_IS_WAITING => [
                "uz" => "To'lov kutish holatida",
                "ru" => "Оплата в режиме ожидания",
                "en" => "Payment is in standby mode"
            ],
            self::ORDER_IS_ALREADY_PAID => [
                "uz" => "Buyurtma uchun to'lov qilib bo'lingan",
                "ru" => "Уже оплачен заказ.",
                "en" => "Already paid for the order"
            ],

            self::AMOUNT_NOT_ENOUGH => [
                "uz" => "Mablag' yetarli emas",
                "ru" => "Суммы недостаточно",
                "en" => "Amount not enough"
            ],
            self::ERROR_WHILE_SAVING_ORDER => [
                "uz" => "Buyurtma ma'lumotlarini saqlashda xatolik yuz berdi.",
                "ru" => "Произошла ошибка при сохранении информации о заказе.",
                "en" => "An error occurred while storing order information."
            ],
            self::NO_AUTH => [
                "uz" => "Noto'g'ri avtorizatsiya",
                "ru" => "Неверная авторизация",
                "en" => "Invalid authorization"
            ],


        ];

        return $messages[$code] ?? [];
    }

    /**
     * @param $orderId
     * @return array
     */
    public function successCheckPerformTransaction($orderId = null)
    {
        $order = Order::findOne($orderId);

        if ($order) {
            return $this->success([
                "allow" => true,
                "detail" => [
                    "receipt_type" => 0, //тип фискального чека
                    "items" => [
                        [
                            "title" => 'Konferensiyalar, ko’rgazmalar, treninglar, tadbirlarni tashkil eish xizmatlari (to’y tadbirlaridan tashqari)', //нааименование товара или услуги
                            "price" => $order->price * 100, //цена за единицу товара или услуги, сумма указана в тийинах
                            "count" => 1, //кол-во товаров или услуг
                            "code" => '10715001001000000', // код *ИКПУ обязательное поле
//                            "units" => 0, //значение изменится в зависимости от вида товара
                            "vat_percent" => 0, //обязательное поле, процент уплачиваемого НДС для данного товара или услуги
                            "package_code" => '1495338' //Код упаковки для конкретного товара или услуги, содержится на сайте в деталях найденного ИКПУ.
                        ]
                    ],
                ],
                // end
            ]);
        }

        return $this->success([
            "allow" => true,
        ]);
    }

}
