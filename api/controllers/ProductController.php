<?php

namespace api\controllers;


use api\models\ProductDetail\CategoryCharacter;
use api\models\ProductDetail\ProductCharacter;
use Yii;
use api\models\ProductDetail\Product;
use api\utils\MessageConst;
use yii\web\NotFoundHttpException;
use api\models\ProductDetail\RecommendedProduct;

class ProductController extends ApiBaseController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @throws NotFoundHttpException
     */
    public function actionDetail($slug)
    {

        $product = Product::findOne(['slug' => $slug]);

        if ($product === null) {
            throw new NotFoundHttpException(MessageConst::NOT_FOUND_MESSAGE);
        }

        $data = [
            'products' => $product,
            'characters' => $this->getCharacters($product),
        ];
        return $this->success([$data], MessageConst::GET_SUCCESS);
    }


    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionFavorites(): array
    {
        $slugs = Yii::$app->request->get('slug');
        if (empty($slugs)) {
            throw new NotFoundHttpException(MessageConst::NOT_FOUND_MESSAGE);
        }

        Product::setFields([
            "slug",
            "name",
            'category_id',
            'categoryName' => function ($model) {
                return $model->category->name ?? '';
            },
            'price',
            'discount_price' => 'sum',
            'image',
        ]);

        $products = Product::find()
            ->where(['in', 'slug', $slugs])
            ->all();

        return $this->success($products, MessageConst::GET_SUCCESS);
    }

    /**
     * @param $product
     * @return array
     */
    private function recProduct($product): array
    {
        return RecommendedProduct::find()
            ->andWhere(['<=', 'published_at', time()])
            ->andWhere(['>=', 'expired_at', time()])
            ->andWhere(['brand_id' => $product->brand_id])
            ->andWhere(['!=', 'id', $product->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(4)
            ->all();
    }

    /**
     * @param $product
     * @return array
     */
    private function getCharacters($product): array
    {
        $productCharacterIds = ProductCharacter::find()
            ->select('category_character_id')
            ->andWhere(['in', 'product_id', $product->id])
            ->column();


        $cat = CategoryCharacter::find()
            ->where(['in', 'id', $productCharacterIds])
            ->all();

        $productAllCharacters = array();

        foreach ($cat as $item) {
            $productAllCharacters[] = [
                'id' => $item->id,
                'name' => $item->name,
                'product_char' => ProductCharacter::find()
                    ->andWhere(['product_id' => $product->id])
                    ->andWhere(['category_character_id' => $item->id])
                    ->all(),
            ];

        }
        return $productAllCharacters;
    }
}