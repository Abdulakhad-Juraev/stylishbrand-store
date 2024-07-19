<?php

namespace common\modules\order\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\order\models\OrderItem;

class OrderItemSearch extends OrderItem
{

    public function rules()
    {
        return [
            [['id', 'order_id','count', 'price', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['total_price','product'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($query=null, $defaultPageSize = 20, $params=null)
    {

        if($params === null){
            $params = Yii::$app->request->queryParams;
        }
        if($query == null){
            $query = OrderItem::find();
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
            ->andFilterWhere(['like', 'count', $this->count])
            ->andFilterWhere(['like', 'price', $this->price])
//            ->andFilterWhere(['like', 'product_id', $this->product_id])
        ;

        return $dataProvider;
    }
}
