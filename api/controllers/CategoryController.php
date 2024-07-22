<?php

namespace api\controllers;

use Yii;
use api\utils\MessageConst;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

use api\models\CategoryPage\AssignProductSize;
use api\models\CategoryPage\Brand;
use api\models\CategoryPage\Category;
use api\models\CategoryPage\Product;
use api\models\CategoryPage\ProductColor;
use api\models\CategoryPage\ProductImage;
use api\models\CategoryPage\ProductSize;

class CategoryController extends ApiBaseController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @return array
     */
    public function actionIndex()
    {
        return $this->success(Category::find()->orderBy(['id' => SORT_DESC, 'sort_order' => SORT_ASC])->active()->all(), MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionPageList(): array
    {
        $params = Yii::$app->request->queryParams;

        $category = Category::findActiveModel(!empty($params['category_id']));

        $query = $category->getProducts();
        $query
            ->andWhere(['<=', 'published_at', time()])
            ->andWhere(['>=', 'expired_at', time()]);
        $this->applyFilters($query, $params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $data = [
            'products' => $dataProvider,
            'subCategories' => $category->subCategories,
            'interestingCategories' => $category->getInterestingCategories($params['category_id']),
            'filters' => $this->getFilters($query->column()),
        ];
        return $this->success($data, MessageConst::GET_SUCCESS);
    }

    /**
     * @param $query
     * @param $params
     * @return void
     */
    private function applyFilters($query, $params): void
    {
        if (!empty($params['search_key'])) {
            $query->joinWith('translations')
                ->andFilterWhere(['like', 'product_lang.name', $params['search_key']]);
        }

        if (!empty($params['subcategory_id'])) {
            $query->andWhere(['in', 'sub_category_id', $params['subcategory_id']]);
        }

        if (!empty($params['is_stock'])) {
            $query->andFilterWhere(['is_stock' => true]);
        }

        if (!empty($params['size_id'])) {
            $query
                ->joinWith('assignProductSizes')
                ->andWhere(['in', 'assign_product_size.size_id', $params['size_id']]);
        }
        if (!empty($params['brand_id'])) {
            $query->andWhere(['in', 'brand_id', $params['brand_id']]);
        }

        if (!empty($params['color_id'])) {
            $query->joinWith('productsByColor')
                ->andWhere(['in', 'product_image.color_id', $params['color_id']]);
        }

        if (!empty($params['min_price'])) {
            $query->andFilterWhere(['>=', 'price', $params['min_price']]);
        }

        if (!empty($params['max_price'])) {
            $query->andFilterWhere(['<=', 'price', $params['max_price']]);
        }
    }

    /**
     * @param $productsId
     * @return array
     */
    private function getFilters($productsId): array
    {
        $productSizeAssignSizeIds = AssignProductSize::find()
            ->select(['size_id'])
            ->andWhere(['in', 'product_id', $productsId])
            ->column();

        $sizes = ProductSize::find()
            ->andWhere(['in', 'id', $productSizeAssignSizeIds])
            ->all();


        $brandIds = Product::find()
            ->select(['brand_id'])
            ->andWhere(['in', 'id', $productsId])
            ->column();

        $brands = Brand::find()
            ->andWhere(['in', 'id', $brandIds])
            ->all();

        $productImage = ProductImage::find()
            ->select('color_id')
            ->andWhere(['in', 'product_id', $productsId])
            ->column();

        $colors = ProductColor::find()
            ->andWhere(['in', 'id', $productImage])
            ->all();

        $prices = Product::find()
            ->select(['min_price' => 'MIN(price)', 'max_price' => 'MAX(price)'])
            ->andWhere(['in', 'id', $productsId])
            ->asArray()
            ->one();

        return [
            'sizes' => $sizes,
            'brands' => $brands,
            'colors' => $colors,
            'productPrices' => [
                'min' => $prices['min_price'],
                'max' => $prices['max_price'],
            ],
        ];
    }
}