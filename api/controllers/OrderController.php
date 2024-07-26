<?php

namespace api\controllers;

use common\modules\order\models\Order;
use common\modules\order\models\OrderItem;
use Yii;
use api\models\ProductDetail\Product;
use api\utils\MessageConst;
use yii\web\NotFoundHttpException;
use api\models\ProductDetail\RecommendedProduct;

class OrderController extends ApiBaseController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];


    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionOrder(): array
    {
        $allData = json_decode(Yii::$app->request->getRawBody(), true);


        if ($allData && $allData['products']) {

            $order = new Order();

            $order->fullname = $allData['fullname'];
            $order->phone = $allData['phone'];
            $order->order_type = strval(Order::$waited);
            $order->payment_type = strval(Order::$type_cash);

            $order->save();


            foreach ($allData['products'] as $item) {

                $product = Product::findOne($item['slug']);

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->count = OrderItem::STATUS_ACTIVE;
                $orderItem->price = $product->price;
                $orderItem->total_price = ($product->price * $orderItem->count);
                $orderItem->save();
            }

            $order->total_price = $order->getOrderItems()->sum('total_price');

            $order->save();

        }

        return $this->success(MessageConst::SAVED_MESSAGE);
    }

}