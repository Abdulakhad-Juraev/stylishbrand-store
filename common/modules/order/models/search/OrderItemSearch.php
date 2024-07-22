<?php
namespace common\modules\order\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\order\models\OrderItem;
use common\modules\product\models\Product; // Ensure the correct namespace

class OrderItemSearch extends OrderItem
{
    public $product;

    public function rules()
    {
        return [
            [['id', 'order_id', 'count', 'price', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['total_price'], 'number'],
            [['product_id', 'product'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($query = null, $defaultPageSize = 20, $params = null)
    {
        if ($params === null) {
            $params = Yii::$app->request->queryParams;
        }
        if ($query === null) {
            $query = OrderItem::find()->joinWith('product');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => $defaultPageSize,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query
            ->andFilterWhere(['like', 'total_price', $this->total_price])
            ->andFilterWhere(['like', 'price', $this->price])
            ->andFilterWhere(['like', 'count', $this->count]);

        // Ensure 'product' relation and 'name' column match your database
        $query->andFilterWhere(['like', 'product.name', $this->product]);

        return $dataProvider;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}

