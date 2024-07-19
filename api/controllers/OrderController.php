<?php

namespace api\controllers;


use api\models\Order;
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

        $order = new Order();

        if ($allData && $allData['products']) {

            $order->save();

            foreach ($allData['products'] as $item) {

                $product = Product::findOne($item['product_id']);

                $orderItem = new OrderItem();

                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->count = 1;
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