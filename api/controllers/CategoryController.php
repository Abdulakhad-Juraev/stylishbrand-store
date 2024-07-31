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
    public function actionIndex(): array
    {
        return $this->success(Category::find()->orderBy(['id' => SORT_DESC, 'sort_order' => SORT_ASC])->active()->all(), MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionPageList(): array
    {

//        $params = Yii::$app->request->queryParams;

        $category_id = Yii::$app->request->get('category_id');
        $subCategoryIds = Yii::$app->request->get('subcategory_id');
        $searchKey = Yii::$app->request->get('search_key');
        $is_stock = Yii::$app->request->get('is_stock');

        $size_id = Yii::$app->request->get('size_id');
        $brand_id = Yii::$app->request->get('brand_id');
        $color_id = Yii::$app->request->get('color_id');
        $min_price = Yii::$app->request->get('min_price');
        $max_price = Yii::$app->request->get('max_price');

        $category = Category::findActiveModel($category_id);

        $query = $category->getProducts();

        $query
            ->andWhere(['<=', 'published_at', time()])
            ->andWhere(['>=', 'expired_at', time()]);


        if (!empty($searchKey)) {
            $query->joinWith('translations')
                ->andFilterWhere(['like', 'product_lang.name', $searchKey]);
        }

        if (!empty($subCategoryIds)) {
            $query->andWhere(['in', 'sub_category_id', $subCategoryIds]);
        }

        if (!empty($is_stock)) {
            $query->andFilterWhere(['is_stock' => true]);
        }

        if (!empty($size_id)) {
            $query
                ->joinWith('assignProductSizes')
                ->andWhere(['in', 'assign_product_size.size_id', $size_id]);
        }
        if (!empty($brand_id)) {
            $query->andWhere(['in', 'brand_id', $brand_id]);
        }

        if (!empty($color_id)) {
            $query->joinWith('productsByColor')
                ->andWhere(['in', 'product_image.color_id', $color_id]);
        }

        if (!empty($min_price)) {
            $query
                ->orderBy(['price'=>SORT_DESC])
                ->andWhere(['>=', 'price', $min_price]);
        }

        if (!empty($max_price)) {
            $query
                ->orderBy(['price'=>SORT_DESC])
                ->andWhere(['<=', 'price', $max_price]);
        }

        $productIds = $query->column();

        $productSizeAssignSizeIds = AssignProductSize::find()
            ->select(['size_id'])
            ->andWhere(['in', 'product_id', $productIds])
            ->column();

        $sizes = ProductSize::find()
            ->andWhere(['in', 'id', $productSizeAssignSizeIds])
            ->all();


        $brandIds = Product::find()
            ->select(['brand_id'])
            ->andWhere(['in', 'id', $productIds])
            ->column();

        $brands = Brand::find()
            ->andWhere(['in', 'id', $brandIds])
            ->all();

        $productImage = ProductImage::find()
            ->select('color_id')
            ->andWhere(['in', 'product_id', $productIds])
            ->column();

        $colors = ProductColor::find()
            ->andWhere(['in', 'id', $productImage])
            ->all();

        $prices = Product::find()
            ->select(['min_price' => 'MIN(price)', 'max_price' => 'MAX(price)'])
            ->andWhere(['in', 'id', $productIds])
            ->asArray()
            ->one();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $data = [
            'products' => $dataProvider,
            'subCategories' => $category->subCategories,
            'interestingCategories' => $category->getInterestingCategories($category_id),
            'filter' => [
                'sizes' => $sizes,
                'brands' => $brands,
                'colors' => $colors,
                'productPrices' => [
                    'min' => $prices['min_price'],
                    'max' => $prices['max_price'],
                ],
            ],
        ];
        return $this->success($data, MessageConst::GET_SUCCESS);
    }
}