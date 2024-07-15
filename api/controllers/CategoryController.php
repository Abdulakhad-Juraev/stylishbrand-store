<?php

namespace api\controllers;

use Yii;
use api\models\Brand;
use yii\db\Expression;
use api\models\Product;
use api\models\Category;
use api\utils\MessageConst;
use api\models\ProductSize;
use api\models\ProductImage;
use api\models\ProductColor;
use yii\data\ActiveDataProvider;
use api\models\AssignProductSize;
use yii\web\NotFoundHttpException;
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
        return $this->success(Category::find()->orderBy(['id' => SORT_ASC])->active()->all(), MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     */
    public function actionHomeHeader(): array
    {
        $category = Category::find()->orderBy(['sort_order' => SORT_ASC])->active()->all();
        return $this->success($category, MessageConst::GET_SUCCESS);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionPageList(): array
    {
        $category_id = Yii::$app->request->get('category_id');
        $subCategoryIds = Yii::$app->request->get('subcategory_id');
        $searchKey = Yii::$app->request->get('search_key');
        $is_stock = Yii::$app->request->get('is_stock');

        $size_id = Yii::$app->request->get('size_id');
        $brand_id = Yii::$app->request->get('brand_id');
        $color_id = Yii::$app->request->get('color_id');
        $min_price = Yii::$app->request->get('min_price');
        $max_price = Yii::$app->request->get('min_price');

        Category::setFields([
            'id',
            'name',
            'image' => 'imageUrl',
        ]);

        Product::setFields([
            'id',
            'name',
            'price',
            'discount_price' => 'sum',
            'image',
        ]);

        $category = Category::findActiveModel($category_id);
        $query = $category->getProducts();

        if ($searchKey) {
            $query->joinWith('translations')
                ->andFilterWhere(['like', 'product_lang.name', $searchKey]);
        }

        if ($subCategoryIds) {
            $query->andWhere(['in', 'sub_category_id', $subCategoryIds]);
        }

        if ($is_stock) {
            $query->andFilterWhere(['is_stock' => true]);
        }

        if ($size_id) {
            $query->joinWith('assignProductSizes')
                ->andWhere(['in', 'assign_product_size.size_id', $size_id]);
        }

        if ($brand_id) {
            $query->andWhere(['in', 'brand_id', $brand_id]);
        }

        if ($color_id) {
            $query->joinWith('productImageColor')
                ->andWhere(['in', 'product_image.color_id', $color_id]);
        }

        if ($min_price) {
            $query->andFilterWhere(['>=', 'price', $min_price]);
        }

        if ($max_price) {
            $query->andFilterWhere(['<=', 'price', $max_price]);
        }

        /** Get Random Interesting Categories */
        /*
        $interestingCategories = Category::find()
            ->andWhere(['!=', 'id', $category_id])
            ->orderBy(new Expression('rand()'))
            ->limit(3)
            ->active()
            ->all();
        $category->getInterestingCategories($category);
        */
        $productIds = $query->column();

        $productSizeAssignSizeIds = AssignProductSize::find()
            ->select(['size_id'])
            ->andWhere(['in', 'product_id', $productIds])
            ->column();

        $productSizes = ProductSize::find()
            ->andWhere(['in', 'id', $productSizeAssignSizeIds])
            ->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

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

        $productColor = ProductColor::find()
            ->andWhere(['in', 'id', $productImage])
            ->all();

        $data = [
            'products' => $dataProvider,
            'subCategories' => $category->subCategories,
            'category' => $category,
            'interestingCategories' => $category->getInterestingCategories($category_id),
            'characters' => [
                'sizes' => $productSizes,
                'brands' => $brands,
                'colors' => $productColor,
                'productPrices' => [
                    'min' => $category->getMinPrice($productIds),
                    'max' => $category->getMaxPrice($productIds),
                ],
            ],
        ];
        return $this->success($data, MessageConst::GET_SUCCESS);
    }


}